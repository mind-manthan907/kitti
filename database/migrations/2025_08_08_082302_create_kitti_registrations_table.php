<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kitti_registrations', function (Blueprint $table) {
            $table->id();
            
            // Step 1: Personal Information
            $table->string('full_name');
            $table->string('mobile')->unique();
            $table->string('email');
            $table->boolean('mobile_verified')->default(false);
            $table->boolean('email_verified')->default(false);
            
            // Step 2: Plan Selection
            $table->enum('plan_amount', ['1000', '10000', '50000', '100000']);
            
            // Step 3: Documents
            $table->string('document_type'); // 'aadhar' or 'pan'
            $table->string('document_file_path');
            $table->string('document_number')->nullable();
            
            // Step 4: Duration
            $table->integer('duration_months')->default(12);
            $table->date('start_date')->nullable();
            $table->date('maturity_date')->nullable();
            
            // Step 5: Account/Payment Info
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_account_holder_name')->nullable();
            $table->string('upi_id')->nullable();
            
            // Registration Status
            $table->enum('status', ['pending', 'payment_pending', 'payment_verified', 'approved', 'rejected', 'discontinued'])->default('pending');
            $table->text('rejection_reason')->nullable();
            
            // Admin Fields
            $table->string('admin_credentials_id')->nullable();
            $table->string('admin_credentials_password')->nullable();
            $table->timestamp('auto_confirm_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Terms and Conditions
            $table->boolean('terms_accepted')->default(false);
            
            // Progress tracking for multi-step form
            $table->integer('form_step')->default(1);
            $table->json('form_data')->nullable(); // Store partial form data
            
            $table->timestamps();
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitti_registrations');
    }
};
