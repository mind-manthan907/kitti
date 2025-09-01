<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvestmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'duration_months',
        'interest_rate',
        'description',
        'is_active',
        'min_duration_months',
        'max_duration_months',
        'emi_months'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get active plans only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculate maturity date based on start date and duration
     */
    public function calculateMaturityDate($startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now();
        return $startDate->copy()->addMonths($this->duration_months);
    }

    /**
     * Calculate monthly due amount
     */
    public function getMonthlyDueAttribute()
    {
        return $this->amount / $this->emi_months;
    }

    /**
     * Get formatted monthly due
     */
    public function getFormattedMonthlyDueAttribute()
    {
        return '₹' . number_format($this->monthly_due, 0);
    }

    /**
     * Calculate projected returns based on monthly contribution
     */
    public function calculateProjectedReturns($monthsPaid = null)
    {
        $monthsPaid = $monthsPaid ?: $this->duration_months;
        $totalContributed = $this->monthly_due * $monthsPaid;
        $annualRate = $this->interest_rate / 100;
        $years = $monthsPaid / 12;
        
        // Simple interest calculation on total contributed amount
        $interest = $totalContributed * $annualRate * $years;
        return $totalContributed + $interest;
    }

    /**
     * Get the KITTI registrations for this plan
     */
    public function kittiRegistrations()
    {
        return $this->hasMany(\App\Models\KittiRegistration::class, 'plan_amount', 'amount');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 0);
    }

    public function getFormattedTargetAmountAttribute()
    {
        return '₹' . number_format($this->amount + ($this->amount * $this->interest_rate) /100 , 0);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->duration_months == 12) {
            return '1 Year';
        } elseif ($this->duration_months < 12) {
            return $this->duration_months . ' Months';
        } else {
            $years = floor($this->duration_months / 12);
            $months = $this->duration_months % 12;
            if ($months == 0) {
                return $years . ' Year' . ($years > 1 ? 's' : '');
            } else {
                return $years . ' Year' . ($years > 1 ? 's' : '') . ' ' . $months . ' Month' . ($months > 1 ? 's' : '');
            }
        }
    }
}
