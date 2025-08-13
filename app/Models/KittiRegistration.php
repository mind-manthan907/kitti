<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class KittiRegistration extends Model
{
    protected $fillable = [
        'full_name',
        'mobile',
        'email',
        'mobile_verified',
        'email_verified',
        'plan_amount',
        'document_type',
        'document_file_path',
        'document_number',
        'duration_months',
        'start_date',
        'maturity_date',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_account_holder_name',
        'upi_id',
        'status',
        'rejection_reason',
        'admin_credentials_id',
        'admin_credentials_password',
        'auto_confirm_at',
        'approved_by',
        'approved_at',
        'terms_accepted',
        'form_step',
        'form_data',
    ];

    protected $casts = [
        'mobile_verified' => 'boolean',
        'email_verified' => 'boolean',
        'terms_accepted' => 'boolean',
        'start_date' => 'date',
        'maturity_date' => 'date',
        'auto_confirm_at' => 'datetime',
        'approved_at' => 'datetime',
        'form_data' => 'array',
    ];

    /**
     * Get the admin who approved this registration
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get payment transactions for this registration
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Get the latest payment transaction
     */
    public function latestPayment(): HasOne
    {
        return $this->hasOne(PaymentTransaction::class)->latest();
    }

    /**
     * Get discontinue requests for this registration
     */
    public function discontinueRequests(): HasMany
    {
        return $this->hasMany(DiscontinueRequest::class);
    }

    /**
     * Get the latest discontinue request
     */
    public function latestDiscontinueRequest(): HasOne
    {
        return $this->hasOne(DiscontinueRequest::class)->latest();
    }

    /**
     * Calculate maturity date based on start date and duration
     */
    public function calculateMaturityDate(): void
    {
        if ($this->start_date && $this->duration_months) {
            // Ensure duration_months is an integer
            $durationMonths = (int) $this->duration_months;
            $this->maturity_date = Carbon::parse($this->start_date)->addMonths($durationMonths);
        }
    }

    /**
     * Get the payment amount (10 months payment for 12 months benefit)
     */
    public function getPaymentAmount(): float
    {
        return (float) $this->plan_amount * 10 / 12;
    }

    /**
     * Check if registration is eligible for auto-confirmation
     */
    public function isEligibleForAutoConfirm(): bool
    {
        return $this->status === 'payment_verified' && 
               $this->auto_confirm_at && 
               $this->auto_confirm_at->isPast();
    }

    /**
     * Get masked bank account number for admin display
     */
    public function getMaskedBankAccount(): string
    {
        if (!$this->bank_account_number) {
            return '';
        }
        
        $length = strlen($this->bank_account_number);
        if ($length <= 4) {
            return str_repeat('*', $length);
        }
        
        return substr($this->bank_account_number, 0, 2) . 
               str_repeat('*', $length - 4) . 
               substr($this->bank_account_number, -2);
    }

    /**
     * Get masked UPI ID for admin display
     */
    public function getMaskedUpiId(): string
    {
        if (!$this->upi_id) {
            return '';
        }
        
        $parts = explode('@', $this->upi_id);
        if (count($parts) !== 2) {
            return str_repeat('*', strlen($this->upi_id));
        }
        
        $username = $parts[0];
        $provider = $parts[1];
        
        if (strlen($username) <= 2) {
            return str_repeat('*', strlen($username)) . '@' . $provider;
        }
        
        return substr($username, 0, 1) . 
               str_repeat('*', strlen($username) - 2) . 
               substr($username, -1) . '@' . $provider;
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved registrations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for payment verified registrations
     */
    public function scopePaymentVerified($query)
    {
        return $query->where('status', 'payment_verified');
    }
}
