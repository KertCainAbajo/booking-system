<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the archive bookings command to run daily at midnight
Schedule::command('bookings:archive')->dailyAt('00:00');

// Remind customers the day before a confirmed booking (8 AM, and again at 6 PM for bookings confirmed later in the day)
Schedule::command('bookings:remind')->twiceDaily(8, 18);

// Delete read notifications older than 3 months
Schedule::call(fn () => \Illuminate\Support\Facades\DB::table('notifications')
    ->whereNotNull('read_at')->where('created_at', '<', now()->subMonths(3))->delete())->daily();
