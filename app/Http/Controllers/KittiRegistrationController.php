<?php

namespace App\Http\Controllers;

use App\Models\KittiRegistration;
use App\Models\PaymentTransaction;
use App\Models\SystemConfig;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class KittiRegistrationController extends Controller
{
    /**
     * Display the registration form
     */
    public function index()
    {
        return view('registration.index');
    }

    /**
     * Show the multi-step registration form
     */
    public function create()
    {
        return view('registration.create');
    }

    /**
     * Show investment plan form for logged-in users
     */
    public function showInvestmentPlanForm()
    {
        $user = Auth::user();

        // Check if user already has an active investment
        $activeInvestment = $user->kittiRegistrations()
            ->where('status', 'approved')
            ->first();
            
        if ($activeInvestment) {
            return redirect()->route('user.dashboard')
                ->with('warning', 'You already have an active investment plan.');
        }

        // Get all active investment plans
        $plans = \App\Models\InvestmentPlan::active()->orderBy('amount')->get();
        return view('registration.investment_plan', compact('user', 'plans'));
    }

    /**
     * Store investment plan for logged-in users
     */
    public function storeInvestmentPlan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'duration_months' => 'required|integer|min:6|max:60',
        ]);

        try {
            // Get the selected plan
            $plan = \App\Models\InvestmentPlan::findOrFail($request->plan_id);

            // Validate duration against plan constraints
            $durationMonths = (int)$request->duration_months;

            // Log the values for debugging
            \Log::info('Investment plan creation - duration validation:', [
                'requested_duration' => $request->duration_months,
                'casted_duration' => $durationMonths,
                'plan_min_duration' => $plan->min_duration_months,
                'plan_max_duration' => $plan->max_duration_months,
                'plan_name' => $plan->name
            ]);

            if ($durationMonths < $plan->min_duration_months || $durationMonths > $plan->max_duration_months) {
                return response()->json([
                    'success' => false,
                    'message' => "Duration must be between {$plan->min_duration_months} and {$plan->max_duration_months} months for this plan."
                ], 422);
            }

            if (!$user->bankAccounts()->primary()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please add a primary bank account first.'
                ], 422);
            }

            // Calculate dates
            $startDate = now();
            $maturityDate = $startDate->copy()->addMonths($durationMonths);

            // Log the calculated dates for debugging
            \Log::info('Investment plan creation - date calculation:', [
                'start_date' => $startDate->format('Y-m-d'),
                'maturity_date' => $maturityDate->format('Y-m-d'),
                'duration_months' => $durationMonths
            ]);

            // Create the investment registration
            $registration = KittiRegistration::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'full_name' => $user->name,
                'mobile' => $user->mobile ?? $user->phone ?? '',
                'email' => $user->email,
                'mobile_verified' => true,
                'email_verified' => true,
                'plan_amount' => $plan->amount,
                'document_type' => $user->approvedKycDocument->document_type ?? null,
                'document_file_path' => $user->approvedKycDocument->document_file_path ?? null,
                'document_number' => $user->approvedKycDocument->document_number ?? null,
                'duration_months' => $durationMonths,
                'start_date' => $startDate,
                'maturity_date' => $maturityDate,
                'bank_account_holder_name' => $user->bankAccounts()->primary()->first()->account_holder_name ?? null,
                'bank_account_number' => $user->bankAccounts()->primary()->first()->account_number ?? null,
                'bank_ifsc_code' => $user->bankAccounts()->primary()->first()->ifsc_code ?? null,
                'upi_id' => $user->upiAccounts()->primary()->first()->upi_id ?? null,
                'terms_accepted' => true,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Investment plan created successfully! Your registration is pending admin approval.',
                'redirect_url' => route('user.dashboard')
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating investment plan: ' . $e->getMessage(), [
                'user_id' => $user->id ?? 'null',
                'plan_id' => $request->plan_id ?? 'null',
                'duration_months' => $request->duration_months ?? 'null',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment plan. You already have an active investment plan. Please try again later.'
            ], 500);
        }
    }

    /**
     * Store step 1: Personal Information
     */
    public function storeStep1(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|size:10|unique:kitti_registrations,mobile',
            'email' => 'required|email|max:255',
        ]);

        // Generate OTP for mobile verification
        $otp = rand(100000, 999999);

        // Store in session for verification
        session([
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'mobile_otp' => (string) $otp,
            'mobile_otp_expires' => now()->addMinutes(10),
        ]);

        // TODO: Send OTP via SMS
        // For now, we'll just store it in session

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your mobile number. For testing, OTP is: ' . $otp,
            'step' => 2
        ]);
    }

    /**
     * Verify mobile OTP
     */
    public function verifyMobileOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $storedOtp = session('mobile_otp');
        $otpExpires = session('mobile_otp_expires');

        if (!$storedOtp || !$otpExpires || now()->isAfter($otpExpires)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or invalid'
            ], 400);
        }

        // Convert both to strings for comparison
        $submittedOtp = (string) $request->otp;
        $storedOtpString = (string) $storedOtp;

        if ($submittedOtp !== $storedOtpString) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Submitted: ' . $submittedOtp . ', Stored: ' . $storedOtpString
            ], 400);
        }

        // Mark mobile as verified
        session(['mobile_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Mobile number verified successfully',
            'step' => 2
        ]);
    }

    /**
     * Store step 2: OTP Verification
     */
    public function storeStep2(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        // For now, we'll just mark mobile as verified
        // In a real application, you would verify the OTP against what was sent
        session([
            'mobile_verified' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mobile number verified successfully!',
        ]);
    }

    /**
     * Store step 3: Plan Selection
     */
    public function storeStep3(Request $request)
    {
        $request->validate([
            'plan_amount' => 'required|in:1000,10000,50000,100000',
        ]);

        if (!session('mobile_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your mobile number first'
            ], 400);
        }

        session([
            'plan_amount' => $request->plan_amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan selected successfully!',
        ]);
    }



    /**
     * Store step 4: Document Upload
     */
    public function storeStep4(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:aadhar,pan',
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'document_number' => 'nullable|string|max:255',
        ]);

        $file = $request->file('document_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        session([
            'document_type' => $request->document_type,
            'document_path' => $filePath,
            'document_number' => $request->document_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully!',
        ]);
    }

    /**
     * Store step 5: Duration Selection
     */
    public function storeStep5(Request $request)
    {
        \Log::info('storeStep5 called with data:', $request->all());

        $request->validate([
            'duration_months' => 'required|integer|min:1|max:60',
        ]);

        session([
            'duration_months' => $request->duration_months,
        ]);

        // Calculate maturity date
        $startDate = now();
        $maturityDate = $startDate->copy()->addMonths((int)$request->duration_months);

        \Log::info('Calculated dates:', [
            'start_date' => $startDate->format('Y-m-d'),
            'maturity_date' => $maturityDate->format('Y-m-d'),
            'duration_months' => $request->duration_months
        ]);

        session([
            'start_date' => $startDate->format('Y-m-d'),
            'maturity_date' => $maturityDate->format('Y-m-d'),
        ]);

        \Log::info('Session data after step 5:', [
            'duration_months' => session('duration_months'),
            'start_date' => session('start_date'),
            'maturity_date' => session('maturity_date'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Duration selected successfully!',
        ]);
    }

    /**
     * Store step 6: Account & Payment Information
     */
    public function storeStep6(Request $request)
    {
        $request->validate([
            'bank_account_holder_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|regex:/^[0-9]+$/|min:9|max:18',
            'bank_ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/|size:11',
            'upi_id' => 'nullable|string|regex:/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/|max:255',
        ], [
            'bank_account_number.regex' => 'Bank account number must contain only numbers.',
            'bank_account_number.min' => 'Bank account number must be at least 9 digits.',
            'bank_account_number.max' => 'Bank account number cannot exceed 18 digits.',
            'bank_ifsc_code.regex' => 'IFSC code must be in valid format (e.g., SBIN0001234).',
            'bank_ifsc_code.size' => 'IFSC code must be exactly 11 characters.',
            'upi_id.regex' => 'UPI ID must be in valid format (e.g., username@upi).',
        ]);

        // Ensure at least one payment method is provided
        if (!$request->bank_account_number && !$request->upi_id) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide either bank account details or UPI ID'
            ], 400);
        }

        session([
            'bank_account_holder_name' => $request->bank_account_holder_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_ifsc_code' => $request->bank_ifsc_code,
            'upi_id' => $request->upi_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account details saved successfully!',
        ]);
    }

    /**
     * Show registration preview
     */
    public function preview()
    {
        $user = Auth::user();

        $registration = $user->kittiRegistrations()
            ->where('status', 'approved')
            ->with('investmentPlan', 'paymentTransactions')
            ->latest()
            ->first();

        return view('registration.preview', compact('registration'));
    }

    /**
     * Show payment page
     */
    public function payment(KittiRegistration $registration)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $emi_amount = $registration->investmentPlan->amount / $registration->investmentPlan->emi_months;

        // Find existing pending transaction
        $transaction = $registration->paymentTransactions()->where('status', 'pending')->latest()->first();

        if (! $transaction) {
            // Create new Razorpay order
            $razorpayOrder = $api->order->create([
                'receipt'  => 'order_' . time(),
                'amount'   => $emi_amount * 100,
                'currency' => 'INR',
            ]);

            $transaction = PaymentTransaction::create([
                'kitti_registration_id' => $registration->id,
                'transaction_reference' => $razorpayOrder['receipt'], // internal
                'gateway_transaction_id' => $razorpayOrder['id'],    // Razorpay order id
                'amount'                => $emi_amount,
                'payment_method'        => 'gateway',
                'status'                => 'pending',
                'payment_initiated_at'  => now(),
            ]);
        } else {
            // Fetch existing Razorpay order using correct column
            $razorpayOrder = $api->order->fetch($transaction->gateway_transaction_id);
        }

        return view('registration.payment', [
            'registration'  => $registration,
            'razorpayOrder' => $razorpayOrder,
            'transaction'   => $transaction,
        ]);
    }


    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        try {
            // Verify signature
            $generatedSignature = hash_hmac(
                'sha256',
                $request->razorpay_order_id . "|" . $request->razorpay_payment_id,
                config('services.razorpay.secret')
            );

            if ($generatedSignature !== $request->razorpay_signature) {
                throw new \Exception("Invalid payment signature");
            }

            // Find transaction by order_id
            $transaction = PaymentTransaction::where('gateway_transaction_id', $request->razorpay_order_id)->first();

            if (! $transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }

            // Update transaction
            $transaction->update([
                'status'                 => 'success',
                'transaction_reference'  => $request->razorpay_payment_id,
                'gateway_transaction_id' => $request->razorpay_order_id,
                'payment_completed_at'   => now(),
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route('registration.preview', $transaction->kitti_registration_id),
            ]);
        } catch (\Exception $e) {
            $this->paymentFailure($request);
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed.',
            ], 400);
        }
    }

    /**
     * On payment failure
     */
    protected function paymentFailure(Request $request)
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $request->razorpay_order_id)->first();

        if ($transaction) {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json([
            'redirect_url' => route('registration.preview', $transaction->registration->id),
        ]);
    }

    /**
     * Get payment redirect URL based on method
     */
    private function getPaymentRedirectUrl(string $method, PaymentTransaction $transaction): string
    {
        return match ($method) {
            'gateway' => route('payment.gateway', $transaction->id),
            'upi' => route('payment.upi', $transaction->id),
            'qr' => route('payment.qr', $transaction->id),
            default => route('registration.payment', $transaction->registration_id),
        };
    }

    /**
     * Send payment initiated email
     */
    private function sendPaymentInitiatedEmail(KittiRegistration $registration, PaymentTransaction $transaction): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        // TODO: Implement email sending
        // Mail::to($registration->email)->send(new PaymentInitiatedMail($registration, $transaction));
    }

    /**
     * Send payment success email
     */
    private function sendPaymentSuccessEmail(KittiRegistration $registration, PaymentTransaction $transaction): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        // TODO: Implement email sending
        // Mail::to($registration->email)->send(new PaymentSuccessMail($registration, $transaction));
    }

    /**
     * Send admin notification
     */
    private function sendAdminNotification(KittiRegistration $registration, PaymentTransaction $transaction): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        $adminEmail = SystemConfig::getValue('admin_email', 'admin@kitti.com');

        // TODO: Implement email sending
        // Mail::to($adminEmail)->send(new AdminNotificationMail($registration, $transaction));
    }

    /**
     * Create user account after registration
     */
    public function createAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Debug: Log session data
            \Log::info('Session data for account creation:', [
                'full_name' => session('full_name'),
                'email' => session('email'),
                'mobile' => session('mobile'),
                'plan_amount' => session('plan_amount'),
                'document_type' => session('document_type'),
                'document_path' => session('document_path'),
                'document_number' => session('document_number'),
                'duration_months' => session('duration_months'),
                'start_date' => session('start_date'),
                'maturity_date' => session('maturity_date'),
                'bank_account_holder_name' => session('bank_account_holder_name'),
                'bank_account_number' => session('bank_account_number'),
                'bank_ifsc_code' => session('bank_ifsc_code'),
                'upi_id' => session('upi_id'),
                'skipInvestmentPlan' => session('skipInvestmentPlan'),
            ]);

            // Get all the data from session
            $userData = [
                'name' => session('full_name'),
                'email' => session('email'),
                'mobile' => session('mobile'),
                'password' => bcrypt($request->password),
                'role' => 'user',
            ];

            // Create the user
            $user = User::create($userData);

            // If user chose to invest, create the registration
            if (!session('skipInvestmentPlan', false)) {
                \Log::info('User chose to invest, creating registration');

                // Validate that all required investment fields are present
                $requiredFields = [
                    'plan_amount',
                    'document_type',
                    'document_path',
                    'duration_months',
                    'start_date',
                    'maturity_date',
                    'bank_account_holder_name',
                    'bank_account_number',
                    'bank_ifsc_code'
                ];

                $missingFields = [];
                foreach ($requiredFields as $field) {
                    if (empty(session($field))) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    \Log::warning('Missing required investment fields', ['missing_fields' => $missingFields]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Missing required investment information: ' . implode(', ', $missingFields)
                    ], 400);
                }

                $registrationData = [
                    'user_id' => $user->id,
                    'plan_id' => session('plan_id'), // Add plan_id from session
                    'full_name' => session('full_name'),
                    'mobile' => session('mobile'),
                    'email' => session('email'),
                    'mobile_verified' => true,
                    'email_verified' => false,
                    'plan_amount' => session('plan_amount'),
                    'document_type' => session('document_type'),
                    'document_file_path' => session('document_path'),
                    'document_number' => session('document_number'),
                    'duration_months' => session('duration_months'),
                    'start_date' => session('start_date'),
                    'maturity_date' => session('maturity_date'),
                    'bank_account_holder_name' => session('bank_account_holder_name'),
                    'bank_account_number' => session('bank_account_number'),
                    'bank_ifsc_code' => session('bank_ifsc_code'),
                    'upi_id' => session('upi_id'),
                    'terms_accepted' => true,
                    'status' => 'pending',
                ];

                $registration = KittiRegistration::create($registrationData);

                // Clear session data
                $this->clearRegistrationSession();

                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully! Your investment registration is pending admin approval.',
                    'redirect_url' => route('auth.login')
                ]);
            } else {
                \Log::info('User skipped investment plan, only creating user account');
                // User skipped investment plan - just create the user account
                $this->clearRegistrationSession();

                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully! You can join investment plans later from your dashboard.',
                    'redirect_url' => route('auth.login')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Account creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create account: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle skipping investment plan
     */
    public function skipInvestment(Request $request)
    {
        // Log the request
        \Log::info('skipInvestment called', [
            'request' => $request->all(),
            'session_before' => session()->all()
        ]);

        // Set the skip flag in session
        session([
            'skipInvestmentPlan' => true,
        ]);

        // Log the session after setting the flag
        \Log::info('Session after setting skipInvestmentPlan', [
            'skipInvestmentPlan' => session('skipInvestmentPlan'),
            'session_all' => session()->all()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Investment plan skipped successfully!',
        ]);
    }

    /**
     * Store step 7: Terms & Conditions
     */
    public function storeStep7(Request $request)
    {
        $request->validate([
            'terms_accepted' => 'required|accepted',
        ]);

        // Store terms acceptance in session
        session([
            'terms_accepted' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terms accepted successfully!',
        ]);
    }

    /**
     * Clear registration session data
     */
    private function clearRegistrationSession()
    {
        // Clear all registration session data
        session()->forget([
            'full_name',
            'mobile',
            'email',
            'plan_amount',
            'document_path',
            'duration_months',
            'start_date',
            'maturity_date',
            'bank_account_holder_name',
            'bank_account_number',
            'bank_ifsc_code',
            'upi_id',
            'terms_accepted',
            'skipInvestmentPlan'
        ]);
    }
}
