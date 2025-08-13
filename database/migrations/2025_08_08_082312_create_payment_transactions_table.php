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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            
            // Registration Reference
            $table->unsignedBigInteger('kitti_registration_id');
            $table->foreign('kitti_registration_id')->references('id')->on('kitti_registrations')->onDelete('cascade');
            
            // Payment Details
            $table->string('transaction_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['gateway', 'upi', 'qr']);
            $table->enum('status', ['pending', 'success', 'failed', 'refunded']);
            
            // Gateway Details
            $table->string('gateway_name')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            
            // UPI Details
            $table->string('upi_transaction_id')->nullable();
            $table->string('upi_app')->nullable();
            
            // Timestamps
            $table->timestamp('payment_initiated_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            
            // Error tracking
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
