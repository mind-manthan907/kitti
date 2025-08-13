<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KittiRegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Registration routes
Route::prefix('registration')->name('registration.')->group(function () {
    Route::get('/', [KittiRegistrationController::class, 'index'])->name('index');
    Route::get('/create', [KittiRegistrationController::class, 'create'])->name('create');
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

// User panel routes (require authentication)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/payment-history', [UserController::class, 'paymentHistory'])->name('payment-history');
    Route::get('/receipt/{payment}', [UserController::class, 'downloadReceipt'])->name('receipt');
    Route::post('/discontinue-request', [UserController::class, 'requestDiscontinue'])->name('discontinue-request');
    Route::get('/discontinue-requests', [UserController::class, 'discontinueRequests'])->name('discontinue-requests');
    Route::get('/security', [UserController::class, 'security'])->name('security');
    Route::post('/toggle-2fa', [UserController::class, 'toggle2FA'])->name('toggle-2fa');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    Route::post('/verify-password-reset', [UserController::class, 'verifyPasswordReset'])->name('verify-password-reset');
});

// Admin routes (require authentication and admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Registrations
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('registrations.index');
    Route::get('/registrations/{registration}', [AdminController::class, 'showRegistration'])->name('registrations.show');
    Route::post('/registrations/{registration}/approve', [AdminController::class, 'approveRegistration'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [AdminController::class, 'rejectRegistration'])->name('registrations.reject');
    
    // Payments
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
    Route::post('/payments/{payment}/mark-success', [AdminController::class, 'markPaymentSuccess'])->name('payments.mark-success');
    
    // Discontinue requests
    Route::get('/discontinue-requests', [AdminController::class, 'discontinueRequests'])->name('discontinue-requests.index');
    Route::get('/discontinue-requests/{request}', [AdminController::class, 'showDiscontinueRequest'])->name('discontinue-requests.show');
    Route::post('/discontinue-requests/{request}/approve', [AdminController::class, 'approveDiscontinueRequest'])->name('discontinue-requests.approve');
    Route::post('/discontinue-requests/{request}/reject', [AdminController::class, 'rejectDiscontinueRequest'])->name('discontinue-requests.reject');
    
    // System configuration
    Route::get('/system-config', [AdminController::class, 'systemConfig'])->name('system-config');
    Route::put('/system-config', [AdminController::class, 'updateSystemConfig'])->name('system-config.update');
    
    // Audit logs
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
