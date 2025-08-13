<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KittiRegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\InvestmentPlanController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Redirect old login route to new auth.login route
Route::get('/login', function () {
    return redirect()->route('auth.login');
})->name('login');

// Registration routes
Route::prefix('registration')->name('registration.')->group(function () {
    // Redirect old registration/create to auth/register
    Route::get('/create', function () {
        return redirect()->route('auth.register');
    })->name('create');
    
    // New route for logged-in users to create investment plans
    Route::middleware(['auth'])->group(function () {
        Route::get('/investment-plan', [KittiRegistrationController::class, 'showInvestmentPlanForm'])->name('investment-plan');
        Route::post('/investment-plan', [KittiRegistrationController::class, 'storeInvestmentPlan'])->name('investment-plan.store');
    });
    
    Route::post('/step1', [KittiRegistrationController::class, 'storeStep1'])->name('step1');
    Route::post('/verify-otp', [KittiRegistrationController::class, 'verifyMobileOtp'])->name('verify-otp');
    Route::post('/step2', [KittiRegistrationController::class, 'storeStep2'])->name('step2');
    Route::post('/step3', [KittiRegistrationController::class, 'storeStep3'])->name('step3');
    Route::post('/step4', [KittiRegistrationController::class, 'storeStep4'])->name('step4');
    Route::post('/step5', [KittiRegistrationController::class, 'storeStep5'])->name('step5');
    Route::post('/step6', [KittiRegistrationController::class, 'storeStep6'])->name('step6');
    Route::post('/step7', [KittiRegistrationController::class, 'storeStep7'])->name('step7');
    Route::post('/skip-investment', [KittiRegistrationController::class, 'skipInvestment'])->name('skip-investment');
    Route::post('/create-account', [KittiRegistrationController::class, 'createAccount'])->name('create-account');
    Route::get('/preview', [KittiRegistrationController::class, 'preview'])->name('preview');
    Route::get('/{registration}/payment', [KittiRegistrationController::class, 'payment'])->name('payment');
    Route::post('/{registration}/process-payment', [KittiRegistrationController::class, 'processPayment'])->name('process-payment');
    Route::get('/payment/success', [KittiRegistrationController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failure', [KittiRegistrationController::class, 'paymentFailure'])->name('payment.failure');
});

// Payment routes
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/gateway/{transaction}', [PaymentController::class, 'gateway'])->name('gateway');
    Route::get('/upi/{transaction}', [PaymentController::class, 'upi'])->name('upi');
    Route::get('/qr/{transaction}', [PaymentController::class, 'qr'])->name('qr');
    Route::post('/gateway/callback', [PaymentController::class, 'gatewayCallback'])->name('gateway.callback');
    Route::post('/upi/callback', [PaymentController::class, 'upiCallback'])->name('upi.callback');
    Route::post('/qr/callback', [PaymentController::class, 'qrCallback'])->name('qr.callback');
    Route::get('/{transaction}/status', [PaymentController::class, 'checkStatus'])->name('status');
    Route::post('/{transaction}/retry', [PaymentController::class, 'retryPayment'])->name('retry');
});

// Authentication routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// User routes (require authentication)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/payment-history', [UserController::class, 'paymentHistory'])->name('payment-history');
    Route::get('/download-receipt/{payment}', [UserController::class, 'downloadReceipt'])->name('download-receipt');
    Route::post('/request-discontinue', [UserController::class, 'requestDiscontinue'])->name('request-discontinue');
    Route::get('/discontinue-requests', [UserController::class, 'discontinueRequests'])->name('discontinue-requests');
    Route::get('/security', [UserController::class, 'security'])->name('security');

    // User Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/kyc', [App\Http\Controllers\Profile\KycController::class, 'index'])->name('kyc.index');
        Route::get('/kyc/create', [App\Http\Controllers\Profile\KycController::class, 'create'])->name('kyc.create');
        Route::post('/kyc', [App\Http\Controllers\Profile\KycController::class, 'store'])->name('kyc.store');
        Route::get('/kyc/{kycDocument}', [App\Http\Controllers\Profile\KycController::class, 'show'])->name('kyc.show');
        Route::get('/kyc/{kycDocument}/download', [App\Http\Controllers\Profile\KycController::class, 'download'])->name('kyc.download');
        Route::delete('/kyc/{kycDocument}', [App\Http\Controllers\Profile\KycController::class, 'destroy'])->name('kyc.destroy');
        
        Route::get('/bank-accounts', [App\Http\Controllers\Profile\BankAccountController::class, 'index'])->name('bank-accounts.index');
        Route::get('/bank-accounts/create', [App\Http\Controllers\Profile\BankAccountController::class, 'create'])->name('bank-accounts.create');
        Route::post('/bank-accounts', [App\Http\Controllers\Profile\BankAccountController::class, 'store'])->name('bank-accounts.store');
        Route::get('/bank-accounts/{bankAccount}/edit', [App\Http\Controllers\Profile\BankAccountController::class, 'edit'])->name('bank-accounts.edit');
        Route::put('/bank-accounts/{bankAccount}', [App\Http\Controllers\Profile\BankAccountController::class, 'update'])->name('bank-accounts.update');
        Route::post('/bank-accounts/{bankAccount}/toggle-status', [App\Http\Controllers\Profile\BankAccountController::class, 'toggleStatus'])->name('bank-accounts.toggle-status');
        Route::post('/bank-accounts/{bankAccount}/set-primary', [App\Http\Controllers\Profile\BankAccountController::class, 'setPrimary'])->name('bank-accounts.set-primary');
        
        Route::get('/upi-accounts', [App\Http\Controllers\Profile\UpiAccountController::class, 'index'])->name('upi-accounts.index');
        Route::get('/upi-accounts/create', [App\Http\Controllers\Profile\UpiAccountController::class, 'create'])->name('upi-accounts.create');
        Route::post('/upi-accounts', [App\Http\Controllers\Profile\UpiAccountController::class, 'store'])->name('upi-accounts.store');
        Route::get('/upi-accounts/{upiAccount}/edit', [App\Http\Controllers\Profile\UpiAccountController::class, 'edit'])->name('upi-accounts.edit');
        Route::put('/upi-accounts/{upiAccount}', [App\Http\Controllers\Profile\UpiAccountController::class, 'update'])->name('upi-accounts.update');
        Route::post('/upi-accounts/{upiAccount}/toggle-status', [App\Http\Controllers\Profile\UpiAccountController::class, 'toggleStatus'])->name('upi-accounts.toggle-status');
        Route::post('/upi-accounts/{upiAccount}/set-primary', [App\Http\Controllers\Profile\UpiAccountController::class, 'setPrimary'])->name('upi-accounts.set-primary');
    });
});

// Admin routes (require authentication and admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    
    // Investment Plans Management
    Route::resource('investment-plans', InvestmentPlanController::class);
    Route::post('/investment-plans/{investmentPlan}/toggle-status', [InvestmentPlanController::class, 'toggleStatus'])->name('investment-plans.toggle-status');
    
    // Registrations
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('registrations.index');
    Route::get('/registrations/{registration}', [AdminController::class, 'showRegistration'])->name('registrations.show');
    Route::post('/registrations/{registration}/approve', [AdminController::class, 'approveRegistration'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [AdminController::class, 'rejectRegistration'])->name('registrations.reject');
    Route::post('/registrations/{registration}/update-payment-status', [AdminController::class, 'updatePaymentStatus'])->name('registrations.update-payment-status');
    
    // Payments
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
    Route::post('/payments/{payment}/mark-success', [AdminController::class, 'markPaymentSuccess'])->name('payments.mark-success');
    
    // Discontinue requests
    Route::get('/discontinue-requests', [AdminController::class, 'discontinueRequests'])->name('discontinue-requests.index');
    Route::get('/discontinue-requests/{request}', [AdminController::class, 'showDiscontinueRequest'])->name('discontinue-requests.show');
    Route::post('/discontinue-requests/{request}/approve', [AdminController::class, 'approveDiscontinueRequest'])->name('discontinue-requests.approve');
    Route::post('/discontinue-requests/{request}/reject', [AdminController::class, 'rejectDiscontinueRequest'])->name('discontinue-requests.reject');
    
    // Monthly Dues
    Route::get('/monthly-dues', [AdminController::class, 'monthlyDues'])->name('monthly-dues');
    Route::post('/monthly-dues/send-reminder', [AdminController::class, 'sendReminder'])->name('monthly-dues.send-reminder');
    
    // System configuration
    Route::get('/system-config', [AdminController::class, 'systemConfig'])->name('system-config');
    Route::put('/system-config', [AdminController::class, 'updateSystemConfig'])->name('system-config.update');
    
    // Audit logs
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
