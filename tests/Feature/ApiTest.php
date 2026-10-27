<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/** End-to-end checks of the JSON API under /api/v1. */
class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected Service $oil;
    protected Service $brakes;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $category = ServiceCategory::create(['name' => 'Maintenance', 'description' => 'Maintenance']);
        $this->oil = Service::create(['name' => 'Change oil', 'description' => 'x', 'service_category_id' => $category->id, 'base_price' => 1500, 'estimated_duration_minutes' => 30, 'is_active' => true]);
        $this->brakes = Service::create(['name' => 'Brake cleaning', 'description' => 'x', 'service_category_id' => $category->id, 'base_price' => 600, 'estimated_duration_minutes' => 45, 'is_active' => true]);
    }

    protected function user(string $role, string $email): User
    {
        $user = User::factory()->create(['email' => $email, 'phone' => '0917' . random_int(1000000, 9999999), 'role_id' => Role::where('name', $role)->value('id')]);
        if ($role === 'customer') {
            Customer::create(['user_id' => $user->id]);
        }

        return $user;
    }

    public function test_register_and_login_return_a_token()
    {
        $this->postJson('/api/v1/register', [
            'name' => 'Api Customer', 'email' => 'api@test.com', 'phone' => '09170001111',
            'password' => 'secret-123', 'password_confirmation' => 'secret-123',
        ])->assertSuccessful();

        $user = User::where('email', 'api@test.com')->firstOrFail();
        $this->assertSame('customer', $user->role->name);
        $this->assertNotNull($user->customer);

        $this->postJson('/api/v1/login', ['email' => 'api@test.com', 'password' => 'secret-123'])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/v1/login', ['email' => 'api@test.com', 'password' => 'wrong'])
            ->assertStatus(401);
    }

    public function test_public_service_list_and_category_filter()
    {
        $this->getJson('/api/v1/services')->assertOk()->assertJsonPath('success', true);
        $this->getJson('/api/v1/services?category_id=' . $this->oil->service_category_id . '&per_page=999999')->assertOk();
        $this->getJson('/api/v1/services/' . $this->oil->id)->assertOk();
    }

    public function test_customer_booking_lifecycle_through_the_api()
    {
        $customer = $this->user('customer', 'c@test.com');
        $vehicle = Vehicle::create(['customer_id' => $customer->customer->id, 'make' => 'Toyota', 'model' => 'Vios', 'year' => 2020, 'plate_number' => 'API 1']);
        Sanctum::actingAs($customer);

        $response = $this->postJson('/api/v1/bookings', [
            'service_ids' => [$this->oil->id, $this->brakes->id],
            'vehicle_id' => $vehicle->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'booking_time' => '10:00',
            'total_amount' => 1, // ignored: the server prices the booking
        ])->assertCreated();

        $bookingId = $response->json('data.id');
        $booking = Booking::findOrFail($bookingId);
        $this->assertEquals(2100, (float) $booking->total_amount);
        $this->assertSame(2, $booking->services()->count());

        $this->getJson('/api/v1/bookings')->assertOk();
        $this->getJson('/api/v1/bookings/' . $bookingId)->assertOk()->assertJsonCount(2, 'data.services');
        $this->getJson('/api/v1/my-bookings')->assertOk();
        $this->getJson('/api/v1/booking-history')->assertOk();

        // Customers can't change the status themselves
        $this->putJson('/api/v1/bookings/' . $bookingId, ['status' => 'completed', 'notes' => 'Please call first'])->assertOk();
        $this->assertSame('pending', $booking->fresh()->status);
        $this->assertSame('Please call first', $booking->fresh()->notes);

        $this->deleteJson('/api/v1/bookings/' . $bookingId)->assertOk();
        $this->assertSame('cancelled', $booking->fresh()->status);
    }

    public function test_customer_cannot_use_someone_elses_car_or_see_their_booking()
    {
        $alice = $this->user('customer', 'alice@test.com');
        $bob = $this->user('customer', 'bob@test.com');
        $bobsCar = Vehicle::create(['customer_id' => $bob->customer->id, 'make' => 'Ford', 'model' => 'Ranger', 'year' => 2021, 'plate_number' => 'BOB 1']);
        $bobsBooking = Booking::create(['customer_id' => $bob->customer->id, 'vehicle_id' => $bobsCar->id, 'booking_date' => now()->addDay(), 'booking_time' => '09:00', 'status' => 'pending', 'total_amount' => 1500]);

        Sanctum::actingAs($alice);
        $this->postJson('/api/v1/bookings', ['service_ids' => [$this->oil->id], 'vehicle_id' => $bobsCar->id, 'booking_date' => now()->addDays(3)->toDateString(), 'booking_time' => '10:00'])
            ->assertForbidden();
        $this->getJson('/api/v1/bookings/' . $bobsBooking->id)->assertForbidden();
        $this->deleteJson('/api/v1/bookings/' . $bobsBooking->id)->assertForbidden();
    }

    public function test_owner_can_change_status_and_it_is_logged()
    {
        $customer = $this->user('customer', 'c2@test.com');
        $car = Vehicle::create(['customer_id' => $customer->customer->id, 'make' => 'Honda', 'model' => 'City', 'year' => 2019, 'plate_number' => 'OWN 1']);
        $booking = Booking::create(['customer_id' => $customer->customer->id, 'vehicle_id' => $car->id, 'booking_date' => now()->addDay(), 'booking_time' => '09:00', 'status' => 'pending', 'total_amount' => 1500]);

        $owner = $this->user('business_owner', 'owner@test.com');
        Sanctum::actingAs($owner);

        $this->putJson('/api/v1/bookings/' . $booking->id, ['status' => 'approved'])->assertOk();
        $this->assertSame('approved', $booking->fresh()->status);
        $this->assertDatabaseHas('booking_status_logs', ['booking_id' => $booking->id, 'new_status' => 'approved', 'changed_by' => $owner->id]);

        $this->putJson('/api/v1/bookings/' . $booking->id, ['status' => 'in_progress'])->assertStatus(422);

        $this->getJson('/api/v1/all-bookings')->assertOk();
        $this->getJson('/api/v1/customers?search=c2@test')->assertOk()->assertJsonPath('data.total', 1);
    }

    public function test_role_guards_on_the_api()
    {
        Sanctum::actingAs($this->user('customer', 'c3@test.com'));
        $this->getJson('/api/v1/all-bookings')->assertForbidden();
        $this->getJson('/api/v1/customers')->assertForbidden();

        $this->app['auth']->forgetGuards();
        $this->getJson('/api/v1/bookings')->assertUnauthorized();
    }
}
