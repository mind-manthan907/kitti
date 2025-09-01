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
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('amount', 12, 2);
            $table->integer('duration_months');
            $table->integer('emi_months');
            $table->decimal('interest_rate', 5, 2)->default(0); // Annual interest rate
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('min_duration_months')->default(12);
            $table->integer('max_duration_months')->default(60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
