<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'not_available' status
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'approved', 'completed', 'cancelled', 'no_show', 'not_available') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any 'not_available' bookings to 'cancelled'
        DB::table('bookings')
            ->where('status', 'not_available')
            ->update(['status' => 'cancelled']);

        // Revert the enum to exclude 'not_available'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'approved', 'completed', 'cancelled', 'no_show') NOT NULL DEFAULT 'pending'");
    }
};
