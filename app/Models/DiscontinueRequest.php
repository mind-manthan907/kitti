<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscontinueRequest extends Model
{
    protected $fillable = [
        'kitti_registration_id',
        'reason',
        'status',
        'admin_notes',
        'payout_amount',
        'payout_method',
        'payout_reference',
        'payout_processed_at',
        'processed_by',
        'processed_at',
        'rejection_reason',

        'bank_account_id',
        'payment_status',
        'razorpay_refund_id',
    ];

    protected $casts = [
        'payout_amount' => 'decimal:2',
        'payout_processed_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the registration this request belongs to
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(KittiRegistration::class, 'kitti_registration_id');
    }

    /**
     * Get the admin who processed this request
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get formatted payout amount
     */
    public function getFormattedPayoutAmount(): string
    {
        return '₹' . number_format($this->payout_amount, 2);
    }

    /**
     * Get payout method display name
     */
    public function getPayoutMethodDisplay(): string
    {
        return match ($this->payout_method) {
            'bank' => 'Bank Transfer',
            'upi' => 'UPI Transfer',
            default => ucfirst($this->payout_method ?? ''),
        };
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function investmentPlan()
    {
        return $this->hasOneThrough(
            InvestmentPlan::class,      // Final model
            KittiRegistration::class,   // Intermediate model
            'id',                       // Foreign key on kitti_registrations (local)
            'id',                       // Foreign key on investment_plans (local)
            'kitti_registration_id',    // Local key on discontinue_requests
            'plan_id'                   // Local key on kitti_registrations
        );
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            KittiRegistration::class,
            'id',                 // Foreign key on kitti_registrations table
            'id',                 // Foreign key on users table
            'kitti_registration_id', // Local key on discontinue_requests table
            'user_id'             // Local key on kitti_registrations table
        );
    }
}
