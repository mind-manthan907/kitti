<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvestmentPlan;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter Plan',
                'amount' => 12000, // Total target: ₹12,000
                'duration_months' => 12,
                'interest_rate' => 8.5,
                'description' => 'Perfect for beginners - pay ₹1,000 monthly for 12 months',
                'min_duration_months' => 12,
                'max_duration_months' => 24,
            ],
            [
                'name' => 'Growth Plan',
                'amount' => 120000, // Total target: ₹1,20,000
                'duration_months' => 12,
                'interest_rate' => 9.0,
                'description' => 'Balanced growth - pay ₹10,000 monthly for 12 months',
                'min_duration_months' => 12,
                'max_duration_months' => 36,
            ],
            [
                'name' => 'Premium Plan',
                'amount' => 600000, // Total target: ₹6,00,000
                'duration_months' => 12,
                'interest_rate' => 9.5,
                'description' => 'High-value investment - pay ₹50,000 monthly for 12 months',
                'min_duration_months' => 12,
                'max_duration_months' => 48,
            ],
            [
                'name' => 'Elite Plan',
                'amount' => 1200000, // Total target: ₹12,00,000
                'duration_months' => 12,
                'interest_rate' => 10.0,
                'description' => 'Elite investment - pay ₹1,00,000 monthly for 12 months',
                'min_duration_months' => 12,
                'max_duration_months' => 60,
            ],
            [
                'name' => 'Long Term Plan',
                'amount' => 240000, // Total target: ₹2,40,000
                'duration_months' => 24,
                'interest_rate' => 9.2,
                'description' => 'Extended duration - pay ₹10,000 monthly for 24 months',
                'min_duration_months' => 24,
                'max_duration_months' => 36,
            ],
        ];

        foreach ($plans as $plan) {
            InvestmentPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }

        $this->command->info('Investment plans seeded successfully!');
    }
}
