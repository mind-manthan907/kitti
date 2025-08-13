<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Get action display name
     */
    public function getActionDisplay(): string
    {
        return match($this->action) {
            'approve_registration' => 'Registration Approved',
            'reject_registration' => 'Registration Rejected',
            'process_payment' => 'Payment Processed',
            'send_credentials' => 'Credentials Sent',
            'approve_discontinue' => 'Discontinue Request Approved',
            'reject_discontinue' => 'Discontinue Request Rejected',
            'process_payout' => 'Payout Processed',
            default => ucwords(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for specific action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
