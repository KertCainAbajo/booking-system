<?php

namespace Tests\Feature;

use App\Livewire\Customer\BookingForm;
use App\Livewire\Guest\GuestBookingForm;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $category = ServiceCategory::create(['name' => 'Maintenance', 'description' => 'Maintenance']);
        $this->service = Service::create([
            'name' => 'Change oil', 'description' => 'Oil change', 'service_category_id' => $category->id,
            'base_price' => 1500, 'estimated_duration_minutes' => 30, 'is_active' => true,
        ]);
    }

    protected function customerWithCar(string $email, string $plate): array
    {
        $user = User::factory()->create(['email' => $email, 'phone' => '09170000000', 'role_id' => Role::where('name', 'customer')->value('id')]);
        $customer = Customer::create(['user_id' => $user->id, 'address' => '1 Test St']);
        $vehicle = Vehicle::create(['customer_id' => $customer->id, 'make' => 'Toyota', 'model' => 'Vios', 'year' => 2020, 'plate_number' => $plate]);

        return [$user, $customer, $vehicle];
    }

    public function test_customer_cannot_set_their_own_price()
    {
        [$user, $customer, $vehicle] = $this->customerWithCar('a@test.com', 'AAA 111');

        Livewire::actingAs($user)->test(BookingForm::class)
            ->set('selectedVehicle', $vehicle->id)
            ->set('selectedServices', [$this->service->id, $this->service->id]) // duplicates too
            ->set('estimatedTotal', 1)                                        // tampered in the browser
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '10:00')
            ->set('policyConsent', true)
            ->call('submitBooking')
            ->assertSet('showSuccessModal', true);

        $booking = Booking::firstOrFail();
        $this->assertEquals(1500, (float) $booking->total_amount);
        $this->assertSame(1, $booking->services()->count());
    }

    public function test_customer_cannot_book_someone_elses_car()
    {
        [$alice] = $this->customerWithCar('alice@test.com', 'ALC 111');
        [, , $bobsCar] = $this->customerWithCar('bob@test.com', 'BOB 222');

        Livewire::actingAs($alice)->test(BookingForm::class)
            ->set('selectedVehicle', $bobsCar->id)
            ->set('selectedServices', [$this->service->id])
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '10:00')
            ->set('policyConsent', true)
            ->call('submitBooking')
            ->assertHasErrors(['selectedVehicle']);

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_guest_booking_is_priced_by_the_server_and_saved_completely()
    {
        Livewire::test(GuestBookingForm::class)
            ->set('customerName', 'Juan Dela Cruz')
            ->set('customerEmail', 'juan@test.com')
            ->set('customerPhone', '09171234567')
            ->set('vehicleMake', 'Honda')
            ->set('vehicleModel', 'City')
            ->set('vehicleYear', 2019)
            ->set('vehiclePlateNumber', 'GST 123')
            ->set('selectedServices', [$this->service->id])
            ->set('estimatedTotal', 1)
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '09:00')
            ->set('policyConsent', true)
            ->call('submitBooking')
            ->assertHasNoErrors()
            ->assertRedirect();

        $booking = Booking::with(['customer', 'vehicle', 'services'])->firstOrFail();
        $this->assertEquals(1500, (float) $booking->total_amount);
        $this->assertTrue((bool) $booking->customer->is_guest);
        $this->assertSame('GST 123', $booking->vehicle->plate_number);
        $this->assertCount(1, $booking->services);
    }

    public function test_hidden_services_cannot_be_booked()
    {
        [$user, , $vehicle] = $this->customerWithCar('c@test.com', 'CCC 333');
        $this->service->update(['is_active' => false]);

        Livewire::actingAs($user)->test(BookingForm::class)
            ->set('selectedVehicle', $vehicle->id)
            ->set('selectedServices', [$this->service->id])
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '10:00')
            ->set('policyConsent', true)
            ->call('submitBooking')
            ->assertSet('showErrorModal', true);

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_references_are_random_and_unique()
    {
        $references = collect(range(1, 200))->map(fn () => Booking::generateBookingReference());

        $this->assertSame(200, $references->unique()->count());
        $references->each(fn ($ref) => $this->assertMatchesRegularExpression('/^BK[A-HJ-NP-Z2-9]{10}$/', $ref));
    }
}
