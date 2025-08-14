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
        Schema::table('kitti_registrations', function (Blueprint $table) {
            // Add plan_id field to reference InvestmentPlan
            $table->unsignedBigInteger('plan_id')->nullable()->after('user_id');
            $table->foreign('plan_id')->references('id')->on('investment_plans')->onDelete('set null');
            
            // Change plan_amount from enum to decimal to support new investment plan amounts
            $table->decimal('plan_amount', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitti_registrations', function (Blueprint $table) {
            // Remove plan_id foreign key and column
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
            
            // Revert plan_amount back to enum (this might cause data loss)
            $table->enum('plan_amount', ['1000', '10000', '50000', '100000'])->change();
        });
    }
};
