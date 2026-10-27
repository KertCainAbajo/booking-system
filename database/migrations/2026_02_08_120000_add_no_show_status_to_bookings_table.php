<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Support\BookingStatusColumn;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'no_show' status
        BookingStatusColumn::set(['pending', 'approved', 'completed', 'cancelled', 'no_show']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any 'no_show' bookings to 'cancelled'
        DB::table('bookings')
            ->where('status', 'no_show')
            ->update(['status' => 'cancelled']);

        // Revert the enum to exclude 'no_show'
        BookingStatusColumn::set(['pending', 'approved', 'completed', 'cancelled']);
    }
};
