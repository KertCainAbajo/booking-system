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
        Schema::table('bookings', function (Blueprint $table) {
            // Add booking reference column without unique constraint first
            $table->string('booking_reference', 20)->nullable()->after('id');
        });

        // Generate booking references for existing bookings
        $bookings = DB::table('bookings')->whereNull('booking_reference')->get();
        foreach ($bookings as $booking) {
            do {
                $reference = 'BK' . strtoupper(substr(uniqid(), -8));
            } while (DB::table('bookings')->where('booking_reference', $reference)->exists());
            
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update(['booking_reference' => $reference]);
        }

        // Now make it required and unique
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_reference', 20)->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_reference');
        });
    }
};
