<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\InvestmentPlan;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'two_factor_enabled',
        'last_login_at',
        'is_active',
        'phone',
        'preferred_payment_method',
        'bank_account_holder_name',
        'bank_account_number',
        'bank_ifsc_code',
        'upi_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'bank_account_number', // Hide sensitive bank details
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Registrations approved by this admin
     */
    public function approvedRegistrations(): HasMany
    {
        return $this->hasMany(KittiRegistration::class, 'approved_by');
    }

    /**
     * Discontinue requests processed by this admin
     */
    public function processedDiscontinueRequests(): HasMany
    {
        return $this->hasMany(DiscontinueRequest::class, 'processed_by');
    }

    /**
     * Audit logs created by this user
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * KITTI registrations for this user
     */
    public function kittiRegistrations(): HasMany
    {
        return $this->hasMany(KittiRegistration::class, 'email', 'email');
    }

    /**
     * Discontinue requests by this user
     */
    public function discontinueRequests(): HasMany
    {
        return $this->hasMany(DiscontinueRequest::class, 'kitti_registration_id', 'kitti_registration_id');
    }

    /**
     * Get the user's KYC documents
     */
    public function kycDocuments()
    {
        return $this->hasMany(KycDocument::class);
    }

    /**
     * Get the user's approved KYC document
     */
    public function approvedKycDocument()
    {
        return $this->hasOne(KycDocument::class)->where('status', 'approved');
    }

    /**
     * Get the user's bank accounts
     */
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * Get the user's UPI accounts
     */
    public function upiAccounts()
    {
        return $this->hasMany(UpiAccount::class);
    }

    /**
     * Check if user has verified KYC
     */
    public function hasVerifiedKyc()
    {
        return $this->kycDocuments()->where('status', 'approved')->exists();
    }

    public function hasPendingKycDocument()
    {
        return $this->kycDocuments()->where('status', 'pending')->exists();
    }

    /**
     * Check if user has any KYC document
     */
    public function hasKycDocument()
    {
        return $this->kycDocuments()->exists();
    }

    /**
     * Check if user has any bank account
     */
    public function hasBankAccount()
    {
        return $this->bankAccounts()->active()->exists();
    }

    /**
     * Check if user has any UPI account
     */
    public function hasUpiAccount()
    {
        return $this->upiAccounts()->active()->exists();
    }

    public function investmentPlans(): HasManyThrough
    {
        return $this->hasManyThrough(
            InvestmentPlan::class,     // Final model
            KittiRegistration::class,  // Intermediate model
            'email',                   // Foreign key on kitti_registrations (points to users.email)
            'id',                      // Foreign key on investment_plans (its PK)
            'email',                   // Local key on users table
            'plan_id'                  // Local key on kitti_registrations table
        );
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }

    public function registrations()
    {
        return $this->hasMany(KittiRegistration::class);
    }

    public function hasApprovedKyc(): bool
    {
        return $this->kycDocuments()->where('status', 'approved')->exists();
    }
}
