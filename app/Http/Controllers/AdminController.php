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
            'total_users' => User::count(),
            'total_registrations' => KittiRegistration::count(),
            'pending_registrations' => KittiRegistration::pending()->count(),
            'approved_registrations' => KittiRegistration::approved()->count(),
            'payment_verified' => KittiRegistration::paymentVerified()->count(),
            'total_payments' => PaymentTransaction::successful()->count(),
            'total_amount' => PaymentTransaction::successful()->sum('amount'),
            'pending_discontinue_requests' => DiscontinueRequest::pending()->count(),
            'overdue_payments' => $this->getOverduePaymentsCount(),
            'blocked_users' => User::where('is_active', false)->count(),
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

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);

        // Calculate stats for the view
        $totalAmount = PaymentTransaction::where('status', 'success')->sum('amount');
        $successfulCount = PaymentTransaction::where('status', 'success')->count();
        $pendingCount = PaymentTransaction::where('status', 'pending')->count();
        $failedCount = PaymentTransaction::where('status', 'failed')->count();

        return view('admin.payments.index', compact('payments', 'totalAmount', 'successfulCount', 'pendingCount', 'failedCount'));
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
     * Update payment status for a registration
     */
    public function updatePaymentStatus(Request $request, KittiRegistration $registration)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,success,failed'
        ]);

        try {
            $registration->update([
                'payment_status' => $request->payment_status,
                'payment_date' => $request->payment_status === 'success' ? now() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating payment status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status'
            ], 500);
        }
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

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->latest()->paginate(20);

        // Calculate stats for the view
        $pendingCount = DiscontinueRequest::where('status', 'pending')->count();
        $approvedCount = DiscontinueRequest::where('status', 'approved')->count();
        $rejectedCount = DiscontinueRequest::where('status', 'rejected')->count();

        return view('admin.discontinue_requests.index', compact('requests', 'pendingCount', 'approvedCount', 'rejectedCount'));
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
     * List all users
     */
    public function users(Request $request)
    {
        $query = User::with(['kittiRegistrations', 'discontinueRequests']);

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->load(['kittiRegistrations', 'discontinueRequests']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Toggle user status (enable/disable)
     */
    public function toggleUserStatus(Request $request, User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $user->is_active ? 'enable_user' : 'disable_user',
            'model_type' => User::class,
            'model_id' => $user->id,
            'description' => "User " . ($user->is_active ? 'enabled' : 'disabled') . " by " . auth()->user()->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User ' . ($user->is_active ? 'enabled' : 'disabled') . ' successfully',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Monthly dues tracking
     */
    public function monthlyDues(Request $request)
    {
        $query = KittiRegistration::with(['paymentTransactions'])
            ->where('status', 'approved');

        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereRaw('MONTH(start_date) = ?', [$month]);
        }

        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereRaw('YEAR(start_date) = ?', [$year]);
        }

        $registrations = $query->get();

        // Calculate monthly dues for each registration
        $monthlyDues = [];
        foreach ($registrations as $registration) {
            $startDate = \Carbon\Carbon::parse($registration->start_date);
            $currentDate = \Carbon\Carbon::now();
            $totalPayments = 10; // 10 months payment for 12 months benefit
            
            for ($i = 0; $i < $totalPayments; $i++) {
                $paymentDate = $startDate->copy()->addMonths($i);
                
                // Check if payment is overdue
                $isOverdue = $paymentDate->isPast() && 
                    !$registration->paymentTransactions()
                        ->whereMonth('payment_completed_at', $paymentDate->month)
                        ->whereYear('payment_completed_at', $paymentDate->year)
                        ->where('status', 'success')
                        ->exists();

                if ($isOverdue) {
                    $monthlyDues[] = [
                        'registration' => $registration,
                        'payment_date' => $paymentDate,
                        'amount' => $registration->getPaymentAmount(),
                        'days_overdue' => $currentDate->diffInDays($paymentDate),
                        'user_email' => $registration->email,
                        'user_name' => $registration->full_name
                    ];
                }
            }
        }

        // Sort by days overdue (most overdue first)
        usort($monthlyDues, function($a, $b) {
            return $b['days_overdue'] <=> $a['days_overdue'];
        });

        return view('admin.monthly_dues', compact('monthlyDues'));
    }

    /**
     * Send reminder for overdue payments
     */
    public function sendReminder(Request $request)
    {
        $request->validate([
            'user_emails' => 'required|array',
            'user_emails.*' => 'email',
            'reminder_message' => 'required|string|max:1000',
        ]);

        $sentCount = 0;
        foreach ($request->user_emails as $email) {
            // TODO: Implement actual email sending
            // Mail::to($email)->send(new PaymentReminderMail($request->reminder_message));
            $sentCount++;
        }

        // Log audit
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'send_payment_reminder',
            'model_type' => null,
            'model_id' => null,
            'description' => "Payment reminder sent to " . $sentCount . " users by " . auth()->user()->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Reminder sent to {$sentCount} users successfully"
        ]);
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

    /**
     * Get count of overdue payments
     */
    private function getOverduePaymentsCount(): int
    {
        $overdueCount = 0;
        $registrations = KittiRegistration::where('status', 'approved')->get();

        foreach ($registrations as $registration) {
            $startDate = \Carbon\Carbon::parse($registration->start_date);
            $currentDate = \Carbon\Carbon::now();
            $totalPayments = 10;

            for ($i = 0; $i < $totalPayments; $i++) {
                $paymentDate = $startDate->copy()->addMonths($i);
                
                if ($paymentDate->isPast() && 
                    !$registration->paymentTransactions()
                        ->whereMonth('payment_completed_at', $paymentDate->month)
                        ->whereYear('payment_completed_at', $paymentDate->year)
                        ->where('status', 'success')
                        ->exists()) {
                    $overdueCount++;
                }
            }
        }

        return $overdueCount;
    }
}
