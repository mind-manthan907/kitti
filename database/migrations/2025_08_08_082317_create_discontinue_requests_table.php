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
        Schema::create('discontinue_requests', function (Blueprint $table) {
            $table->id();
            
            // Registration Reference
            $table->unsignedBigInteger('kitti_registration_id');
            $table->foreign('kitti_registration_id')->references('id')->on('kitti_registrations')->onDelete('cascade');
            
            // Request Details
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            
            // Processing Details
            $table->decimal('payout_amount', 10, 2)->nullable();
            $table->string('payout_method')->nullable(); // 'bank' or 'upi'
            $table->string('payout_reference')->nullable();
            $table->timestamp('payout_processed_at')->nullable();
            
            // Admin Actions
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discontinue_requests');
    }
};
