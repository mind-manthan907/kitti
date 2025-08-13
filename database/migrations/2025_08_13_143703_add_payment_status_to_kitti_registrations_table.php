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
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending')->after('status');
            $table->timestamp('payment_date')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitti_registrations', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_date']);
        });
    }
};
