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
                'amount' => 10000,
                'duration_months' => 12,
                'emi_months' => 10,
                'interest_rate' => 20,
                'description' => 'Perfect for beginners - pay ₹1,000 for 10 months and get benefits of 2 months extra',
                'min_duration_months' => 12,
                'max_duration_months' => 24,
            ],
            [
                'name' => 'Growth Plan',
                'amount' => 50000,
                'duration_months' => 12,
                'emi_months' => 10,
                'interest_rate' => 20,
                'description' => 'Balanced growth - pay ₹50,000 for 10 months and get benefits of 2 months extra',
                'min_duration_months' => 12,
                'max_duration_months' => 36,
            ],
            [
                'name' => 'Premium Plan',
                'amount' => 100000, 
                'duration_months' => 12,
                'emi_months' => 10,
                'interest_rate' => 20,
                'description' => 'High-value investment - pay ₹1,00,000 for 10 months and get benefits of 2 months extra',
                'min_duration_months' => 12,
                'max_duration_months' => 48,
            ],
            [
                'name' => 'Elite Plan',
                'amount' => 1500000,
                'duration_months' => 12,
                'emi_months' => 10,
                'interest_rate' => 20,
                'description' => 'Elite investment - pay ₹1,50,000 for 10 months and get benefits of 2 months extra',
                'min_duration_months' => 12,
                'max_duration_months' => 60,
            ],
            [
                'name' => 'Long Term Plan',
                'amount' => 200000, 
                'duration_months' => 24,
                'emi_months' => 10,
                'interest_rate' => 9.2,
                'description' => 'Extended duration - pay ₹2,00,000 for 10 months and get benefits of 2 months extra',
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
