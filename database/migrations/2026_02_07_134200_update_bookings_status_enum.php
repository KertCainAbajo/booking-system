<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Support\BookingStatusColumn;

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
        BookingStatusColumn::set(['pending', 'approved', 'completed', 'cancelled']);

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
        BookingStatusColumn::set(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled']);

        // Update back to confirmed
        DB::table('bookings')
            ->where('status', 'pending')
            ->whereNotNull('updated_at')
            ->update(['status' => 'confirmed']);
    }
};
