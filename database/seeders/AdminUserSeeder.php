<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a new admin user for testing
        User::create([
            'name' => 'Test Admin',
            'email' => 'testadmin@kitti.com',
            'password' => Hash::make('test123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Test Admin user created successfully!');
        $this->command->info('Email: testadmin@kitti.com');
        $this->command->info('Password: test123');
        
        // Also create a regular user for testing
        if (!User::where('email', 'testuser@kitti.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'testuser@kitti.com',
                'password' => Hash::make('test123'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Test User created successfully!');
            $this->command->info('Email: testuser@kitti.com');
            $this->command->info('Password: test123');
        }
    }
}
