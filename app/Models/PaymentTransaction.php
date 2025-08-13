<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'kitti_registration_id',
        'transaction_reference',
        'amount',
        'payment_method',
        'status',
        'gateway_name',
        'gateway_transaction_id',
        'gateway_response',
        'upi_transaction_id',
        'upi_app',
        'payment_initiated_at',
        'payment_completed_at',
        'error_message',
        'retry_count',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'payment_initiated_at' => 'datetime',
        'payment_completed_at' => 'datetime',
    ];

    /**
     * Get the registration this payment belongs to
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(KittiRegistration::class, 'kitti_registration_id');
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return '₹' . number_format($this->amount, 2);
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplay(): string
    {
        return match($this->payment_method) {
            'gateway' => 'Payment Gateway',
            'upi' => 'UPI Payment',
            'qr' => 'QR Code Payment',
            default => ucfirst($this->payment_method),
        };
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
