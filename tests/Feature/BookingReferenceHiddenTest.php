<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Guest\BookingTracker;

class BookingReferenceHiddenTest extends TestCase
{
    use RefreshDatabase;

    /** A guest booking with the given status (the reference is generated automatically). */
    protected function guestBooking(string $status): Booking
    {
        $customer = Customer::create([
            'name' => 'Test Guest',
            'email' => 'guest@test.com',
            'phone' => '1234567890',
            'is_guest' => true,
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'make' => 'Toyota',
            'model' => 'Vios',
            'year' => 2020,
            'plate_number' => 'GST ' . random_int(1000, 9999),
        ]);

        return Booking::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => $status,
            'total_amount' => 500,
        ]);
    }

    public function test_booking_reference_is_visible_for_pending_bookings()
    {
        $booking = $this->guestBooking('pending');

        $this->assertNotEmpty($booking->booking_reference);
        $this->assertTrue($booking->canShowReference());

        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', false)
            ->assertSet('booking.id', $booking->id);
    }

    public function test_booking_reference_is_hidden_for_completed_bookings()
    {
        $booking = $this->guestBooking('completed');

        $this->assertFalse($booking->canShowReference());

        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', true)
            ->assertSet('booking', null);
    }

    public function test_booking_reference_is_visible_for_approved_bookings()
    {
        $booking = $this->guestBooking('approved');

        $this->assertTrue($booking->canShowReference());

        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', false)
            ->assertSet('booking.id', $booking->id);
    }

    public function test_booking_reference_is_visible_for_cancelled_bookings()
    {
        $booking = $this->guestBooking('cancelled');

        // Cancelled bookings can still be looked up
        $this->assertTrue($booking->canShowReference());

        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', false)
            ->assertSet('booking.id', $booking->id);
    }

    public function test_unknown_reference_is_not_found()
    {
        Livewire::test(BookingTracker::class)
            ->set('bookingReference', 'NOPE-0000')
            ->call('trackBooking')
            ->assertSet('notFound', true);
    }

    public function test_guest_can_cancel_a_pending_booking_from_the_tracker()
    {
        $booking = $this->guestBooking('pending');

        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->call('cancelBooking');

        $this->assertSame('cancelled', $booking->fresh()->status);
        $this->assertDatabaseHas('booking_status_logs', ['booking_id' => $booking->id, 'new_status' => 'cancelled', 'changed_by' => null]);
    }
}
