<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Guest\BookingTracker;

class BookingReferenceHiddenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that booking reference is visible for non-completed bookings
     */
    public function test_booking_reference_is_visible_for_pending_bookings()
    {
        // Create a guest customer
        $customer = Customer::factory()->create([
            'name' => 'Test Guest',
            'email' => 'guest@test.com',
            'phone' => '1234567890',
        ]);

        // Create a vehicle
        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Create a pending booking
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
        ]);

        // Assert that the reference can be shown
        $this->assertTrue($booking->canShowReference());

        // Test booking tracker component
        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', false)
            ->assertSet('booking.id', $booking->id);
    }

    /**
     * Test that booking reference is hidden for completed bookings
     */
    public function test_booking_reference_is_hidden_for_completed_bookings()
    {
        // Create a guest customer
        $customer = Customer::factory()->create([
            'name' => 'Test Guest',
            'email' => 'guest@test.com',
            'phone' => '1234567890',
        ]);

        // Create a vehicle
        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Create a completed booking
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'completed',
        ]);

        // Assert that the reference cannot be shown
        $this->assertFalse($booking->canShowReference());

        // Test booking tracker component
        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', true)
            ->assertSessionHas('error', 'This booking reference is no longer available. The service has been completed.');
    }

    /**
     * Test that booking reference is visible for approved bookings
     */
    public function test_booking_reference_is_visible_for_approved_bookings()
    {
        // Create a guest customer
        $customer = Customer::factory()->create([
            'name' => 'Test Guest',
            'email' => 'guest@test.com',
            'phone' => '1234567890',
        ]);

        // Create a vehicle
        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Create an approved booking
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'approved',
        ]);

        // Assert that the reference can be shown
        $this->assertTrue($booking->canShowReference());

        // Test booking tracker component
        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', false)
            ->assertSet('booking.id', $booking->id);
    }

    /**
     * Test that booking reference is visible for cancelled bookings
     */
    public function test_booking_reference_is_visible_for_cancelled_bookings()
    {
        // Create a guest customer
        $customer = Customer::factory()->create([
            'name' => 'Test Guest',
            'email' => 'guest@test.com',
            'phone' => '1234567890',
        ]);

        // Create a vehicle
        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Create a cancelled booking
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'cancelled',
        ]);

        // Assert that the reference can be shown (cancelled bookings can still be tracked)
        $this->assertTrue($booking->canShowReference());

        // Test booking tracker component
        Livewire::test(BookingTracker::class)
            ->set('bookingReference', $booking->booking_reference)
            ->call('trackBooking')
            ->assertSet('notFound', false)
            ->assertSet('booking.id', $booking->id);
    }
}
