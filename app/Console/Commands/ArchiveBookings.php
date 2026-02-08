<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class ArchiveBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive completed and cancelled bookings from the previous day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to archive bookings...');

        // Get bookings that should be archived
        // - Completed bookings from previous days
        // - Cancelled bookings from previous days
        // - No show bookings from previous days
        $bookingsToArchive = Booking::where('is_archived', false)
            ->whereIn('status', ['completed', 'cancelled', 'no_show'])
            ->whereDate('booking_date', '<', Carbon::today())
            ->get();

        $count = $bookingsToArchive->count();

        if ($count === 0) {
            $this->info('No bookings to archive.');
            return Command::SUCCESS;
        }

        // Archive the bookings
        foreach ($bookingsToArchive as $booking) {
            $booking->update([
                'is_archived' => true,
                'archived_at' => now()
            ]);

            $this->line("Archived booking: {$booking->booking_reference} (Status: {$booking->status})");
        }

        $this->info("Successfully archived {$count} booking(s).");

        return Command::SUCCESS;
    }
}
