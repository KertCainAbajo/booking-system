<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'confirmed' and 'in_progress' statuses to 'approved'
        DB::table('bookings')
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->update(['status' => 'pending']); // Temporarily set to pending

        // Alter the enum column
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'approved', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");

        // Update the temporarily set records to 'approved'
        DB::table('bookings')
            ->where('status', 'pending')
            ->whereNotNull('updated_at')
            ->update(['status' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'approved' back to 'confirmed'
        DB::table('bookings')
            ->where('status', 'approved')
            ->update(['status' => 'pending']); // Temporarily set to pending

        // Restore the original enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");

        // Update back to confirmed
        DB::table('bookings')
            ->where('status', 'pending')
            ->whereNotNull('updated_at')
            ->update(['status' => 'confirmed']);
    }
};
