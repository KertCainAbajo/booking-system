<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Support\BookingNotifier;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:remind';

    protected $description = "Remind customers about their confirmed bookings for tomorrow";

    public function handle()
    {
        $bookings = Booking::with(['customer.user', 'vehicle'])
            ->where('status', 'approved')
            ->whereDate('booking_date', now()->addDay()->toDateString())
            ->whereNull('reminder_sent_at')
            ->get();

        foreach ($bookings as $booking) {
            BookingNotifier::reminder($booking);
            // Saved quietly so the "updated" notifications don't fire again
            $booking->forceFill(['reminder_sent_at' => now()])->saveQuietly();
        }

        $this->info("Sent {$bookings->count()} reminder(s).");

        return Command::SUCCESS;
    }
}
