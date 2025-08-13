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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // User who performed the action
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Action Details
            $table->string('action'); // 'approve_registration', 'reject_registration', 'process_payment', etc.
            $table->string('model_type'); // 'KittiRegistration', 'PaymentTransaction', etc.
            $table->unsignedBigInteger('model_id');
            
            // Changes
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('description');
            
            // IP and User Agent
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
