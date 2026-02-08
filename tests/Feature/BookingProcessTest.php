<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Customer\BookingForm;
use App\Livewire\Staff\BookingDetail;
use App\Livewire\Admin\ServiceManagement;

class BookingProcessTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $staff;
    protected $owner;
    protected $admin;
    protected $vehicle;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        // Create test users
        $customerRole = Role::where('name', 'customer')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $ownerRole = Role::where('name', 'business_owner')->first();
        $adminRole = Role::where('name', 'it_admin')->first();

        // Create customer user
        $this->customer = User::create([
            'name' => 'Test Customer',
            'email' => 'testcustomer@test.com',
            'phone' => '09111111111',
            'password' => bcrypt('password'),
            'role_id' => $customerRole->id,
        ]);

        // Create customer profile
        $customerProfile = Customer::create([
            'user_id' => $this->customer->id,
            'address' => '123 Test Street',
        ]);

        // Create vehicle for customer
        $this->vehicle = Vehicle::create([
            'customer_id' => $customerProfile->id,
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'plate_number' => 'ABC123',
            'vin_number' => 'TEST12345',
        ]);

        // Create staff user
        $this->staff = User::create([
            'name' => 'Test Staff',
            'email' => 'teststaff@test.com',
            'phone' => '09222222222',
            'password' => bcrypt('password'),
            'role_id' => $staffRole->id,
        ]);

        // Create owner user
        $this->owner = User::create([
            'name' => 'Test Owner',
            'email' => 'testowner@test.com',
            'phone' => '09333333333',
            'password' => bcrypt('password'),
            'role_id' => $ownerRole->id,
        ]);

        // Create admin user
        $this->admin = User::create([
            'name' => 'Test Admin',
            'email' => 'testadmin@test.com',
            'phone' => '09444444444',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        // Create service category and service
        $category = ServiceCategory::create([
            'name' => 'Oil Change',
            'description' => 'Oil change services',
        ]);

        $this->service = Service::create([
            'name' => 'Full Synthetic Oil Change',
            'description' => 'Premium oil change service',
            'service_category_id' => $category->id,
            'base_price' => 1500.00,
            'estimated_duration_minutes' => 30,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function customer_can_create_booking()
    {
        $this->actingAs($this->customer);

        Livewire::test(BookingForm::class)
            ->set('selectedVehicle', $this->vehicle->id)
            ->set('selectedServices', [$this->service->id])
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '10:00')
            ->set('notes', 'Test booking')
            ->call('submitBooking')
            ->assertRedirect(route('customer.tracker'));

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function customer_cannot_create_booking_without_services()
    {
        $this->actingAs($this->customer);

        Livewire::test(BookingForm::class)
            ->set('selectedVehicle', $this->vehicle->id)
            ->set('selectedServices', [])
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '10:00')
            ->call('submitBooking')
            ->assertHasErrors(['selectedServices']);
    }

    /** @test */
    public function customer_cannot_create_booking_without_vehicle()
    {
        $this->actingAs($this->customer);

        Livewire::test(BookingForm::class)
            ->set('selectedServices', [$this->service->id])
            ->set('bookingDate', now()->addDay()->format('Y-m-d'))
            ->set('bookingTime', '10:00')
            ->call('submitBooking')
            ->assertHasErrors(['selectedVehicle']);
    }

    /** @test */
    public function customer_can_add_new_vehicle()
    {
        $this->actingAs($this->customer);

        Livewire::test(BookingForm::class)
            ->set('newVehicle.make', 'Honda')
            ->set('newVehicle.model', 'Civic')
            ->set('newVehicle.year', 2021)
            ->set('newVehicle.plate_number', 'XYZ789')
            ->set('newVehicle.vin_number', 'HONDA12345')
            ->call('addNewVehicle')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vehicles', [
            'customer_id' => $this->customer->customer->id,
            'make' => 'Honda',
            'model' => 'Civic',
            'plate_number' => 'XYZ789',
        ]);
    }

    /** @test */
    public function staff_can_view_booking_details()
    {
        $this->actingAs($this->customer);
        
        // Create a booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        $booking->services()->attach($this->service->id, [
            'quantity' => 1,
            'price' => $this->service->base_price,
        ]);

        // Staff views the booking
        $this->actingAs($this->staff);

        Livewire::test(BookingDetail::class, ['id' => $booking->id])
            ->assertSee('Test Customer')
            ->assertSee('Toyota')
            ->assertSee('Corolla')
            ->assertSee('Full Synthetic Oil Change');
    }

    /** @test */
    public function staff_can_update_booking_status()
    {
        // Create a booking as customer
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        // Staff updates status
        $this->actingAs($this->staff);

        Livewire::test(BookingDetail::class, ['id' => $booking->id])
            ->call('updateStatus', 'approved')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'old_status' => 'pending',
            'new_status' => 'approved',
        ]);
    }

    /** @test */
    public function staff_can_assign_booking_to_themselves()
    {
        // Create a booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        // Staff assigns to themselves
        $this->actingAs($this->staff);

        Livewire::test(BookingDetail::class, ['id' => $booking->id])
            ->call('assignToMe')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'assigned_staff_id' => $this->staff->id,
        ]);
    }

    /** @test */
    public function admin_can_create_service()
    {
        $this->actingAs($this->admin);

        $category = ServiceCategory::first();

        Livewire::test(ServiceManagement::class)
            ->set('name', 'Tire Rotation')
            ->set('description', 'Rotate all four tires')
            ->set('service_category_id', $category->id)
            ->set('base_price', 500.00)
            ->set('estimated_duration_minutes', 20)
            ->set('is_active', true)
            ->call('saveService')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('services', [
            'name' => 'Tire Rotation',
            'base_price' => 500.00,
        ]);
    }

    /** @test */
    public function admin_can_update_service()
    {
        $this->actingAs($this->admin);

        Livewire::test(ServiceManagement::class)
            ->call('editService', $this->service->id)
            ->set('base_price', 2000.00)
            ->call('saveService')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('services', [
            'id' => $this->service->id,
            'base_price' => 2000.00,
        ]);
    }

    /** @test */
    public function admin_can_toggle_service_active_status()
    {
        $this->actingAs($this->admin);

        $this->assertTrue($this->service->is_active);

        Livewire::test(ServiceManagement::class)
            ->call('toggleActive', $this->service->id);

        $this->service->refresh();
        $this->assertFalse($this->service->is_active);
    }

    /** @test */
    public function admin_can_delete_service()
    {
        $this->actingAs($this->admin);

        Livewire::test(ServiceManagement::class)
            ->call('deleteService', $this->service->id);

        $this->assertDatabaseMissing('services', [
            'id' => $this->service->id,
        ]);
    }

    /** @test */
    public function customer_can_access_customer_routes()
    {
        $this->actingAs($this->customer);

        $this->get(route('customer.dashboard'))->assertOk();
        $this->get(route('customer.booking'))->assertOk();
        $this->get(route('customer.tracker'))->assertOk();
    }

    /** @test */
    public function staff_can_access_staff_routes()
    {
        $this->actingAs($this->staff);

        $this->get(route('staff.dashboard'))->assertOk();
        $this->get(route('staff.calendar'))->assertOk();
    }

    /** @test */
    public function owner_can_access_owner_routes()
    {
        $this->actingAs($this->owner);

        $this->get(route('owner.dashboard'))->assertOk();
        $this->get(route('owner.reports'))->assertOk();
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $this->actingAs($this->admin);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.users'))->assertOk();
        $this->get(route('admin.services'))->assertOk();
    }

    /** @test */
    public function customer_cannot_access_staff_routes()
    {
        $this->actingAs($this->customer);

        $this->get(route('staff.dashboard'))
            ->assertRedirect(route('customer.dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function staff_cannot_access_admin_routes()
    {
        $this->actingAs($this->staff);

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('staff.dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function booking_calculates_total_correctly()
    {
        $this->actingAs($this->customer);

        // Create another service
        $service2 = Service::create([
            'name' => 'Air Filter Replacement',
            'description' => 'Replace air filter',
            'service_category_id' => $this->service->service_category_id,
            'base_price' => 800.00,
            'estimated_duration_minutes' => 15,
            'is_active' => true,
        ]);

        Livewire::test(BookingForm::class)
            ->set('selectedServices', [$this->service->id, $service2->id])
            ->assertSet('estimatedTotal', 2300.00);
    }

    /** @test */
    public function customer_can_cancel_pending_booking()
    {
        $this->actingAs($this->customer);

        // Create a pending booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        // Customer cancels the booking
        $booking->cancel($this->customer->id, 'Changed my mind');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'old_status' => 'pending',
            'new_status' => 'cancelled',
            'changed_by' => $this->customer->id,
        ]);
    }

    /** @test */
    public function customer_can_cancel_approved_booking()
    {
        $this->actingAs($this->customer);

        // Create an approved booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'approved',
            'total_amount' => 1500.00,
        ]);

        // Should be cancellable
        $this->assertTrue($booking->canBeCancelled());

        $booking->cancel($this->customer->id);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }

    /** @test */
    public function completed_booking_cannot_be_cancelled()
    {
        // Create a completed booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->subDay(),
            'booking_time' => '10:00',
            'status' => 'completed',
            'total_amount' => 1500.00,
        ]);

        $this->assertFalse($booking->canBeCancelled());

        $this->expectException(\Exception::class);
        $booking->cancel($this->customer->id);
    }

    /** @test */
    public function staff_can_cancel_booking()
    {
        $this->actingAs($this->staff);

        // Create a pending booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        Livewire::test(BookingDetail::class, ['id' => $booking->id])
            ->call('updateStatus', 'cancelled')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'new_status' => 'cancelled',
            'changed_by' => $this->staff->id,
        ]);
    }

    /** @test */
    public function admin_can_cancel_booking()
    {
        $this->actingAs($this->admin);

        // Create a pending booking
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        $adminBookingDetail = new \App\Livewire\Admin\BookingDetail();
        $adminBookingDetail->mount($booking->id);
        $adminBookingDetail->updateStatus('cancelled');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'new_status' => 'cancelled',
            'changed_by' => $this->admin->id,
        ]);
    }

    /** @test */
    public function guest_can_cancel_booking_with_reference()
    {
        // Create a booking (simulating a guest booking)
        $booking = Booking::create([
            'customer_id' => $this->customer->customer->id,
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay(),
            'booking_time' => '10:00',
            'status' => 'pending',
            'total_amount' => 1500.00,
        ]);

        // Guest cancels without being logged in
        $booking->cancel(null, 'Cancelled by guest customer');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'new_status' => 'cancelled',
            'changed_by' => null,
        ]);
    }
}
