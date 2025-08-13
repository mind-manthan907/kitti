<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Hash;

class KittiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kitti.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Initialize system configurations
        SystemConfig::initializeDefaults();

        // Create additional system configs
        $additionalConfigs = [
            'gateway_merchant_id' => ['value' => 'TEST_MERCHANT_001', 'type' => 'string', 'description' => 'Payment gateway merchant ID'],
            'gateway_api_key' => ['value' => 'test_api_key_123', 'type' => 'string', 'description' => 'Payment gateway API key'],
            'company_upi_id' => ['value' => 'kitti@paytm', 'type' => 'string', 'description' => 'Company UPI ID for payments'],
            'interest_rate' => ['value' => 12.5, 'type' => 'decimal', 'description' => 'Annual interest rate for KITTI investments'],
            'min_investment' => ['value' => 1000, 'type' => 'integer', 'description' => 'Minimum investment amount'],
            'max_investment' => ['value' => 100000, 'type' => 'integer', 'description' => 'Maximum investment amount'],
            'payment_retry_limit' => ['value' => 3, 'type' => 'integer', 'description' => 'Maximum payment retry attempts'],
            'session_timeout_minutes' => ['value' => 30, 'type' => 'integer', 'description' => 'Session timeout in minutes'],
        ];

        foreach ($additionalConfigs as $key => $config) {
            SystemConfig::setValue($key, $config['value'], $config['type'], $config['description']);
        }

        $this->command->info('KITTI platform initialized successfully!');
        $this->command->info('Admin credentials: admin@kitti.com / admin123');
    }
}
