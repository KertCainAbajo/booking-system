<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Support\BookingStatusColumn;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'not_available' status
        BookingStatusColumn::set(['pending', 'approved', 'completed', 'cancelled', 'no_show', 'not_available']);
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
        BookingStatusColumn::set(['pending', 'approved', 'completed', 'cancelled', 'no_show']);
    }
};
