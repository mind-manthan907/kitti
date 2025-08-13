<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_holder_name',
        'account_number',
        'bank_name',
        'ifsc_code',
        'branch_name',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the bank account
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get masked account number for display
     */
    public function getMaskedAccountNumberAttribute()
    {
        $number = $this->account_number;
        if (strlen($number) <= 4) {
            return $number;
        }
        return substr($number, 0, 4) . str_repeat('*', strlen($number) - 8) . substr($number, -4);
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for primary accounts
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
