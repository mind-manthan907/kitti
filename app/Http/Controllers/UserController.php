<?php

namespace App\Http\Controllers;

use App\Models\KittiRegistration;
use App\Models\DiscontinueRequest;
use App\Models\PaymentTransaction;
use App\Models\SystemConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * User dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get active registration using email relationship
        $activeRegistration = $user->kittiRegistrations()
            ->where('status', 'approved')
            ->first();
        
        // Calculate total investment
        $totalInvestment = $user->kittiRegistrations()
            ->where('status', 'approved')
            ->sum('plan_amount');
        
        // Get next payment date (if any)
        $nextPaymentDate = null;
        if ($activeRegistration) {
            $nextPaymentDate = $this->getNextPaymentDate($activeRegistration);
        }
        
        return view('user.dashboard', compact('user', 'activeRegistration', 'totalInvestment', 'nextPaymentDate'));
    }

    /**
     * User profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        $registration = $user->kittiRegistrations()->first();

        return view('user.profile', compact('user', 'registration'));
    }

    /**
     * Update user profile including payment methods
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'preferred_payment_method' => 'nullable|in:gateway,upi,qr',
            'bank_account_holder_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc_code' => 'nullable|string|max:20',
            'upi_id' => 'nullable|string|max:100',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            // Update or create payment method preferences
            $user->update([
                'preferred_payment_method' => $request->preferred_payment_method,
                'bank_account_holder_name' => $request->bank_account_holder_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_ifsc_code' => $request->bank_ifsc_code,
                'upi_id' => $request->upi_id,
            ]);

            return redirect()->route('user.profile')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage());

            return redirect()->route('user.profile')
                ->with('success', 'Failed to update profile. Please try again.');


        }
    }

    /**
     * Payment history
     */
    public function paymentHistory()
    {
        $user = Auth::user();
        
        $registration = $user->kittiRegistrations()->first();

        if (!$registration) {
            return redirect()->route('user.dashboard')
                ->with('error', 'No KITTI registration found');
        }

        $payments = $registration->paymentTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate stats for the view
        $totalPaid = $registration->paymentTransactions()
            ->where('status', 'success')
            ->sum('amount');
        $successfulCount = $registration->paymentTransactions()
            ->where('status', 'success')
            ->count();
        $pendingCount = $registration->paymentTransactions()
            ->where('status', 'pending')
            ->count();

        return view('user.payment_history', compact('registration', 'payments', 'totalPaid', 'successfulCount', 'pendingCount'));
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt(PaymentTransaction $payment)
    {
        $user = Auth::user();
        
        // Verify user owns this payment
        $registration = $user->kittiRegistrations()->first();

        if (!$registration || $payment->kitti_registration_id !== $registration->id) {
            abort(403, 'Unauthorized');
        }

        // Generate PDF receipt
        $pdf = $this->generateReceiptPdf($payment);
        
        return $pdf->download('receipt_' . $payment->transaction_reference . '.pdf');
    }

    /**
     * Request discontinue
     */
    public function requestDiscontinue(Request $request)
    {
        $user = Auth::user();
        
        $registration = $user->kittiRegistrations()->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'No KITTI registration found'
            ], 400);
        }

        if ($registration->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved registrations can request discontinuation'
            ], 400);
        }

        // Check if there's already a pending request
        $existingRequest = $registration->discontinueRequests()
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending discontinue request'
            ], 400);
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        DiscontinueRequest::create([
            'kitti_registration_id' => $registration->id,
            'email' => $user->email,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Discontinue request submitted successfully'
        ]);
    }

    /**
     * View discontinue requests
     */
    public function discontinueRequests()
    {
        $user = Auth::user();
        
        $registration = $user->kittiRegistrations()->first();

        if (!$registration) {
            return redirect()->route('user.dashboard')
                ->with('error', 'No KITTI registration found');
        }

        $requests = $registration->discontinueRequests()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.discontinue_requests', compact('registration', 'requests'));
    }

    /**
     * Security settings
     */
    public function security()
    {
        $user = Auth::user();
        
        return view('user.security', compact('user'));
    }

    /**
     * Enable/disable 2FA
     */
    public function toggle2FA(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'two_factor_enabled' => 'required|boolean',
        ]);

        $user->update([
            'two_factor_enabled' => $request->two_factor_enabled
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->two_factor_enabled ? '2FA enabled successfully' : '2FA disabled successfully'
        ]);
    }

    /**
     * Reset password via email OTP
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this email address'
            ], 400);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Store in session
        session([
            'password_reset_email' => $request->email,
            'password_reset_otp' => $otp,
            'password_reset_expires' => now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email
        // Mail::to($request->email)->send(new PasswordResetMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'Password reset OTP sent to your email'
        ]);
    }

    /**
     * Verify password reset OTP
     */
    public function verifyPasswordReset(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $storedOtp = session('password_reset_otp');
        $otpExpires = session('password_reset_expires');
        $email = session('password_reset_email');

        if (!$storedOtp || !$otpExpires || now()->isAfter($otpExpires)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or invalid'
            ], 400);
        }

        if ($request->otp !== $storedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Clear session
        session()->forget(['password_reset_email', 'password_reset_otp', 'password_reset_expires']);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Get next payment date
     */
    private function getNextPaymentDate(KittiRegistration $registration): ?string
    {
        if ($registration->status !== 'approved') {
            return null;
        }

        $lastPayment = $registration->paymentTransactions()
            ->where('status', 'success')
            ->latest()
            ->first();

        if (!$lastPayment) {
            return $registration->start_date->format('Y-m-d');
        }

        // Calculate next payment date (monthly payments)
        $nextPaymentDate = $lastPayment->payment_completed_at->addMonth();
        
        // If next payment date is after maturity, return null
        if ($nextPaymentDate->isAfter($registration->maturity_date)) {
            return null;
        }

        return $nextPaymentDate->format('Y-m-d');
    }

    /**
     * Generate receipt PDF
     */
    private function generateReceiptPdf(PaymentTransaction $payment)
    {
        // TODO: Implement PDF generation
        // This would typically use a library like DomPDF or Snappy
        
        $data = [
            'payment' => $payment,
            'registration' => $payment->registration,
            'company_name' => SystemConfig::getValue('company_name', 'KITTI Investment Platform'),
            'company_address' => SystemConfig::getValue('company_address', 'Mumbai, Maharashtra, India'),
        ];

        // For now, return a simple response
        // In a real implementation, you would generate a PDF
        return response()->json($data);
    }
}
