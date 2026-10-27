<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Decides who hears about each step of a booking, and what they're told.
 *
 * Customers (and guests, by email only) hear about every change to their booking.
 * Owners hear about new bookings, customer cancellations and late arrivals.
 * Nobody gets pushed about something they just did themselves.
 */
class BookingNotifier
{
    public static function created(Booking $booking): void
    {
        $when = self::when($booking);

        self::toCustomer($booking, 'received', [
            'title' => 'Booking received',
            'body' => "We got your booking for {$when}. We'll let you know as soon as the shop confirms it.",
            'lines' => self::details($booking),
        ], channels: ['mail', 'database']); // they just booked, so no push

        self::toOwners($booking, 'new_booking', [
            'title' => 'New booking request',
            'body' => self::customerName($booking) . " booked {$when}" . self::vehicleText($booking) . '. Please approve or decline it.',
            'action' => 'Review booking',
        ]);
    }

    public static function statusChanged(Booking $booking, string $new): void
    {
        $when = self::when($booking);
        $byCustomer = self::actorIsCustomer($booking);

        $message = match ($new) {
            'approved' => [
                'title' => 'Booking confirmed',
                'body' => "Your booking for {$when} is confirmed. Please arrive on time.",
                'lines' => self::details($booking),
            ],
            'not_available' => [
                'title' => 'Shop not available that day',
                'body' => "Sorry, we can't take your booking for {$when}. Please book another date.",
                'action' => 'Book another date',
            ],
            'cancelled' => $byCustomer
                ? ['title' => 'Booking cancelled', 'body' => "You cancelled your booking for {$when}."]
                : ['title' => 'Booking cancelled by the shop', 'body' => "Your booking for {$when} was cancelled by the shop. Please contact us or book another date."],
            'completed' => [
                'title' => 'Your car is ready',
                'body' => 'The service is finished. Total: PHP ' . number_format((float) $booking->total_amount, 2) . '. You can pick up your car.',
            ],
            'no_show' => [
                'title' => 'We missed you today',
                'body' => "You didn't make it to your booking for {$when}. You can book a new date any time.",
                'action' => 'Book again',
            ],
            'pending' => [
                'title' => 'Booking back to waiting',
                'body' => "Your booking for {$when} is waiting for the shop to confirm it again.",
            ],
            default => null,
        };

        if ($message) {
            // A customer who cancelled gets the email/bell record, but no push about their own action
            self::toCustomer($booking, $new, $message, channels: $byCustomer ? ['mail', 'database'] : ['mail', 'database', 'push']);
        }

        if ($new === 'cancelled' && $byCustomer) {
            self::toOwners($booking, 'customer_cancelled', [
                'title' => 'Booking cancelled by customer',
                'body' => self::customerName($booking) . " cancelled their booking for {$when}. That slot is free again.",
            ]);
        }
    }

    public static function mechanicAssigned(Booking $booking): void
    {
        $phone = $booking->mechanic_phone ? " ({$booking->mechanic_phone})" : '';

        self::toCustomer($booking, 'mechanic', [
            'title' => 'Mechanic assigned',
            'body' => "{$booking->mechanic_name}{$phone} will work on your car for your booking on " . self::when($booking) . '.',
        ], channels: ['database', 'push']); // small update: bell and push, no email
    }

    public static function markedLate(Booking $booking): void
    {
        $eta = $booking->estimated_arrival_time ? ' New arrival time: ' . date('g:i A', strtotime($booking->estimated_arrival_time)) . '.' : '';
        $reason = $booking->late_reason ? " Reason: {$booking->late_reason}." : '';

        self::toOwners($booking, 'running_late', [
            'title' => 'Customer running late',
            'body' => self::customerName($booking) . ' will be late for ' . self::when($booking) . ".{$eta}{$reason}",
        ], channels: ['database', 'push']);
    }

    public static function reminder(Booking $booking): void
    {
        self::toCustomer($booking, 'reminder', [
            'title' => 'Reminder: your booking is tomorrow',
            'body' => 'See you on ' . self::when($booking) . '. If you can no longer come, please cancel so we can give the slot to someone else.',
            'lines' => self::details($booking),
        ]);
    }

    // ── delivery ──────────────────────────────────────────────────────────

    protected static function toCustomer(Booking $booking, string $event, array $content, array $channels = ['mail', 'database', 'push']): void
    {
        $customer = $booking->customer;
        if (! $customer) {
            return;
        }

        self::send(function () use ($booking, $event, $content, $channels, $customer) {
            if ($customer->user) {
                $customer->user->notify(new BookingUpdate($booking->id, $event, $content, route('customer.tracker'), $channels));
            } elseif ($email = $customer->getContactEmail()) {
                // Guests have no account: email only, with a link to the public tracker
                Notification::route('mail', $email)->notify(new BookingUpdate(
                    $booking->id, $event, $content,
                    route('guest.booking.track', ['reference' => $booking->booking_reference]),
                    ['mail'],
                ));
            }
        });
    }

    protected static function toOwners(Booking $booking, string $event, array $content, array $channels = ['mail', 'database', 'push']): void
    {
        $owners = User::whereHas('role', fn ($q) => $q->where('name', 'business_owner'))
            ->when(Auth::id(), fn ($q, $id) => $q->where('id', '!=', $id))
            ->get();

        if ($owners->isNotEmpty()) {
            self::send(fn () => Notification::send($owners, new BookingUpdate(
                $booking->id, $event, $content, route('owner.booking.detail', $booking->id), $channels,
            )));
        }
    }

    /** A notification problem (mail server down, etc.) must never break saving a booking. */
    protected static function send(callable $deliver): void
    {
        try {
            $deliver();
        } catch (\Throwable $e) {
            Log::error('Booking notification failed', ['error' => $e->getMessage()]);
        }
    }

    // ── wording helpers ──────────────────────────────────────────────────

    protected static function actorIsCustomer(Booking $booking): bool
    {
        $userId = Auth::id();

        // No signed-in user means a guest acting on their own booking from the public tracker
        return $userId === null || $userId === $booking->customer?->user_id;
    }

    protected static function when(Booking $booking): string
    {
        $date = $booking->booking_date?->format('l, F j');
        $time = $booking->booking_time?->format('g:i A');

        return $time ? "{$date} at {$time}" : (string) $date;
    }

    protected static function customerName(Booking $booking): string
    {
        return $booking->customer?->getDisplayName() ?? 'A customer';
    }

    protected static function vehicleText(Booking $booking): string
    {
        $v = $booking->vehicle;

        return $v ? " for a {$v->make} {$v->model}" . ($v->plate_number ? " ({$v->plate_number})" : '') : '';
    }

    /** Booking summary lines for emails. */
    protected static function details(Booking $booking): array
    {
        $lines = ['Reference: ' . $booking->booking_reference];
        if ($v = $booking->vehicle) {
            $lines[] = "Vehicle: {$v->make} {$v->model}" . ($v->plate_number ? " ({$v->plate_number})" : '');
        }

        return $lines;
    }
}
