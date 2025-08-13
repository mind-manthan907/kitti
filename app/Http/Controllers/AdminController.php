<?php

namespace App\Http\Controllers;

use App\Models\KittiRegistration;
use App\Models\PaymentTransaction;
use App\Models\DiscontinueRequest;
use App\Models\AuditLog;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_registrations' => KittiRegistration::count(),
            'pending_registrations' => KittiRegistration::pending()->count(),
            'approved_registrations' => KittiRegistration::approved()->count(),
            'payment_verified' => KittiRegistration::paymentVerified()->count(),
            'total_payments' => PaymentTransaction::successful()->count(),
            'total_amount' => PaymentTransaction::successful()->sum('amount'),
            'pending_discontinue_requests' => DiscontinueRequest::pending()->count(),
        ];

        $recentRegistrations = KittiRegistration::with('latestPayment')
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = PaymentTransaction::with('registration')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'recentPayments'));
    }

    /**
     * List all registrations
     */
    public function registrations(Request $request)
    {
        $query = KittiRegistration::with(['latestPayment', 'approvedBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_amount')) {
            $query->where('plan_amount', $request->plan_amount);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $registrations = $query->latest()->paginate(20);

        return view('admin.registrations.index', compact('registrations'));
    }

    /**
     * Show registration details
     */
    public function showRegistration(KittiRegistration $registration)
    {
        $registration->load(['paymentTransactions', 'discontinueRequests']);
        
        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Approve registration
     */
    public function approveRegistration(Request $request, KittiRegistration $registration)
    {
        $request->validate([
            'admin_credentials_id' => 'required|string|max:255',
            'admin_credentials_password' => 'required|string|min:6',
        ]);

        // Generate secure credentials
        $credentialsId = $request->admin_credentials_id;
        $tempPassword = $request->admin_credentials_password;

        $registration->update([
            'status' => 'approved',
            'admin_credentials_id' => $credentialsId,
            'admin_credentials_password' => Hash::make($tempPassword),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_registration',
            'model_type' => KittiRegistration::class,
            'model_id' => $registration->id,
            'description' => "Registration approved by " . auth()->user()->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send credentials email
        $this->sendCredentialsEmail($registration, $credentialsId, $tempPassword);

        return response()->json([
            'success' => true,
            'message' => 'Registration approved successfully'
        ]);
    }

    /**
     * Reject registration
     */
    public function rejectRegistration(Request $request, KittiRegistration $registration)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject_registration',
            'model_type' => KittiRegistration::class,
            'model_id' => $registration->id,
            'description' => "Registration rejected by " . auth()->user()->name . ". Reason: " . $request->rejection_reason,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send rejection email
        $this->sendRejectionEmail($registration, $request->rejection_reason);

        return response()->json([
            'success' => true,
            'message' => 'Registration rejected successfully'
        ]);
    }

    /**
     * List all payments
     */
    public function payments(Request $request)
    {
        $query = PaymentTransaction::with('registration');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show payment details
     */
    public function showPayment(PaymentTransaction $payment)
    {
        $payment->load('registration');
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Mark payment as successful manually
     */
    public function markPaymentSuccess(Request $request, PaymentTransaction $payment)
    {
        $payment->update([
            'status' => 'success',
            'payment_completed_at' => now(),
        ]);

        // Update registration status
        $registration = $payment->registration;
        $registration->update(['status' => 'payment_verified']);

        // Set auto-confirm time
        $autoConfirmHours = SystemConfig::getValue('auto_confirm_hours', 24);
        $registration->update([
            'auto_confirm_at' => now()->addHours($autoConfirmHours)
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'process_payment',
            'model_type' => PaymentTransaction::class,
            'model_id' => $payment->id,
            'description' => "Payment marked as successful by " . auth()->user()->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment marked as successful'
        ]);
    }

    /**
     * List discontinue requests
     */
    public function discontinueRequests(Request $request)
    {
        $query = DiscontinueRequest::with(['registration', 'processedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(20);

        return view('admin.discontinue_requests.index', compact('requests'));
    }

    /**
     * Show discontinue request details
     */
    public function showDiscontinueRequest(DiscontinueRequest $request)
    {
        $request->load(['registration', 'processedBy']);
        
        return view('admin.discontinue_requests.show', compact('request'));
    }

    /**
     * Approve discontinue request
     */
    public function approveDiscontinueRequest(Request $request, DiscontinueRequest $discontinueRequest)
    {
        $request->validate([
            'payout_amount' => 'required|numeric|min:0',
            'payout_method' => 'required|in:bank,upi',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $discontinueRequest->update([
            'status' => 'approved',
            'payout_amount' => $request->payout_amount,
            'payout_method' => $request->payout_method,
            'admin_notes' => $request->admin_notes,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // Update registration status
        $registration = $discontinueRequest->registration;
        $registration->update(['status' => 'discontinued']);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_discontinue',
            'model_type' => DiscontinueRequest::class,
            'model_id' => $discontinueRequest->id,
            'description' => "Discontinue request approved by " . auth()->user()->name . ". Payout: ₹" . $request->payout_amount,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send approval email
        $this->sendDiscontinueApprovalEmail($discontinueRequest);

        return response()->json([
            'success' => true,
            'message' => 'Discontinue request approved successfully'
        ]);
    }

    /**
     * Reject discontinue request
     */
    public function rejectDiscontinueRequest(Request $request, DiscontinueRequest $discontinueRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $discontinueRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject_discontinue',
            'model_type' => DiscontinueRequest::class,
            'model_id' => $discontinueRequest->id,
            'description' => "Discontinue request rejected by " . auth()->user()->name . ". Reason: " . $request->rejection_reason,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send rejection email
        $this->sendDiscontinueRejectionEmail($discontinueRequest, $request->rejection_reason);

        return response()->json([
            'success' => true,
            'message' => 'Discontinue request rejected successfully'
        ]);
    }

    /**
     * System configuration
     */
    public function systemConfig()
    {
        $configs = SystemConfig::getAllAsArray();
        
        return view('admin.system_config', compact('configs'));
    }

    /**
     * Update system configuration
     */
    public function updateSystemConfig(Request $request)
    {
        $request->validate([
            'auto_confirm_hours' => 'required|integer|min:1|max:168',
            'payment_gateway_enabled' => 'boolean',
            'upi_enabled' => 'boolean',
            'qr_enabled' => 'boolean',
            'email_notifications_enabled' => 'boolean',
            'sms_notifications_enabled' => 'boolean',
            'admin_email' => 'required|email',
            'company_name' => 'required|string|max:255',
            'support_phone' => 'required|string|max:20',
            'support_email' => 'required|email',
        ]);

        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['_token', '_method'])) continue;
            
            $type = match($key) {
                'auto_confirm_hours' => 'integer',
                'payment_gateway_enabled', 'upi_enabled', 'qr_enabled', 
                'email_notifications_enabled', 'sms_notifications_enabled' => 'boolean',
                default => 'string',
            };
            
            SystemConfig::setValue($key, $value, $type);
        }

        return response()->json([
            'success' => true,
            'message' => 'System configuration updated successfully'
        ]);
    }

    /**
     * Audit logs
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(50);

        return view('admin.audit_logs', compact('logs'));
    }

    /**
     * Reports
     */
    public function reports()
    {
        $monthlyStats = KittiRegistration::selectRaw('
            DATE_FORMAT(created_at, "%Y-%m") as month,
            COUNT(*) as total_registrations,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_registrations,
            SUM(CASE WHEN status = "payment_verified" THEN 1 ELSE 0 END) as payment_verified
        ')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        $planStats = KittiRegistration::selectRaw('
            plan_amount,
            COUNT(*) as total,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved
        ')
        ->groupBy('plan_amount')
        ->get();

        $paymentStats = PaymentTransaction::selectRaw('
            payment_method,
            COUNT(*) as total_transactions,
            SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as successful_transactions,
            SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as total_amount
        ')
        ->groupBy('payment_method')
        ->get();

        return view('admin.reports', compact('monthlyStats', 'planStats', 'paymentStats'));
    }

    /**
     * Send credentials email
     */
    private function sendCredentialsEmail(KittiRegistration $registration, string $credentialsId, string $tempPassword): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        // TODO: Implement email sending
        // Mail::to($registration->email)->send(new CredentialsMail($registration, $credentialsId, $tempPassword));
    }

    /**
     * Send rejection email
     */
    private function sendRejectionEmail(KittiRegistration $registration, string $reason): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        // TODO: Implement email sending
        // Mail::to($registration->email)->send(new RejectionMail($registration, $reason));
    }

    /**
     * Send discontinue approval email
     */
    private function sendDiscontinueApprovalEmail(DiscontinueRequest $request): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        // TODO: Implement email sending
        // Mail::to($request->registration->email)->send(new DiscontinueApprovalMail($request));
    }

    /**
     * Send discontinue rejection email
     */
    private function sendDiscontinueRejectionEmail(DiscontinueRequest $request, string $reason): void
    {
        if (!SystemConfig::getValue('email_notifications_enabled', true)) {
            return;
        }

        // TODO: Implement email sending
        // Mail::to($request->registration->email)->send(new DiscontinueRejectionMail($request, $reason));
    }
}
