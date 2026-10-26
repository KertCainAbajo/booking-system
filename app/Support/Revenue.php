<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\CarbonInterface;

/**
 * Shop revenue for a date range, shared by the owner Overview and Reports.
 *
 * Revenue = completed bookings dated in the range, plus paid payments recorded in
 * the range for bookings that are not already counted as completed in it.
 */
class Revenue
{
    public static function between(CarbonInterface $from, CarbonInterface $to): float
    {
        $fromDate = $from->toDateString();
        $toDate = $to->toDateString();

        $completed = Booking::whereBetween('booking_date', [$fromDate, $toDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        $otherPaid = Payment::whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('payment_status', 'paid')
            ->whereHas('booking', function ($query) use ($fromDate, $toDate) {
                $query->where('status', '!=', 'completed')
                      ->orWhereNotBetween('booking_date', [$fromDate, $toDate]);
            })
            ->sum('amount');

        return (float) $completed + (float) $otherPaid;
    }

    /** Percentage change, or null when there is nothing to compare against. */
    public static function change(float $current, float $previous): ?float
    {
        return $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : null;
    }
}
