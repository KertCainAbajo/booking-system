<?php

namespace Tests\Feature;

use App\Livewire\Admin\BookingManagement as AdminBookings;
use App\Livewire\BusinessOwner\RevenueReport;
use App\Livewire\Customer\BookingTracker;
use App\Livewire\Customer\TwoFactorSetup;
use App\Livewire\Guest\GuestBookingForm;
use App\Livewire\Staff\BookingCalendar;
use App\Livewire\Staff\BookingHistory;
use App\Livewire\Staff\BookingManagement as OwnerBookings;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

/** Screens and actions that aren't covered by the booking/API tests. */
class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $owner;
    protected User $admin;
    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $make = fn (string $role, string $email) => User::factory()->create([
            'email' => $email, 'phone' => '09170000000', 'role_id' => Role::where('name', $role)->value('id'),
        ]);
        $this->customer = $make('customer', 'cust@test.com');
        $this->owner = $make('business_owner', 'owner@test.com');
        $this->admin = $make('super_admin', 'admin@test.com');

        $profile = Customer::create(['user_id' => $this->customer->id, 'address' => '1 Test St']);
        $car = Vehicle::create(['customer_id' => $profile->id, 'make' => 'Toyota', 'model' => 'Vios', 'year' => 2020, 'plate_number' => 'WRK 1']);
        $category = ServiceCategory::create(['name' => 'Maintenance', 'description' => 'x']);
        $service = Service::create(['name' => 'Change oil', 'description' => 'x', 'service_category_id' => $category->id, 'base_price' => 1500, 'estimated_duration_minutes' => 30, 'is_active' => true]);

        $this->booking = Booking::create(['customer_id' => $profile->id, 'vehicle_id' => $car->id, 'booking_date' => now()->addDay(), 'booking_time' => '10:00', 'status' => 'approved', 'total_amount' => 1500]);
        $this->booking->services()->attach($service->id, ['quantity' => 1, 'price' => 1500]);
    }

    public function test_customer_can_report_running_late_then_cancel()
    {
        Livewire::actingAs($this->customer)->test(BookingTracker::class)
            ->call('openLateModal', $this->booking->id)
            ->set('estimatedArrivalTime', '10:30')
            ->set('lateReason', 'Traffic')
            ->call('markAsLate')
            ->assertHasNoErrors()
            ->assertSet('showLateModal', false)
            ->call('cancelBooking', $this->booking->id);

        $fresh = $this->booking->fresh();
        $this->assertTrue((bool) $fresh->marked_as_late);
        $this->assertSame('Traffic', $fresh->late_reason);
        $this->assertSame('cancelled', $fresh->status);
    }

    public function test_customer_cannot_touch_another_customers_booking()
    {
        $other = User::factory()->create(['role_id' => Role::where('name', 'customer')->value('id')]);
        Customer::create(['user_id' => $other->id]);

        Livewire::actingAs($other)->test(BookingTracker::class)
            ->call('cancelBooking', $this->booking->id)
            ->call('openLateModal', $this->booking->id)
            ->set('estimatedArrivalTime', '11:00')
            ->call('markAsLate');

        $this->assertSame('approved', $this->booking->fresh()->status);
        $this->assertFalse((bool) $this->booking->fresh()->marked_as_late);
    }

    public function test_two_factor_setup_opens_for_every_role()
    {
        foreach ([$this->customer, $this->owner, $this->admin] as $user) {
            $this->actingAs($user)->get('/two-factor/setup')->assertOk();
        }
    }

    public function test_two_factor_can_be_enabled_and_a_recovery_code_signs_in()
    {
        $setup = Livewire::actingAs($this->owner)->test(TwoFactorSetup::class);
        $code = (new Google2FA())->getCurrentOtp($setup->get('secret'));

        $setup->set('verificationCode', $code)->call('enable2FA')->assertHasNoErrors()
            ->call('finishSetup')->assertRedirect(route('owner.dashboard'));

        $this->owner->refresh();
        $this->assertTrue((bool) $this->owner->google2fa_enabled);
        $recovery = json_decode($this->owner->recovery_codes, true)[0];

        auth()->logout();

        // Password step asks for the code, then a recovery code completes the sign-in
        $login = Volt::test('pages.auth.login')
            ->set('form.email', 'owner@test.com')
            ->set('form.password', 'password')
            ->call('login')
            ->assertSet('form.requires_2fa', true);
        $this->assertGuest();

        $login->set('form.otp_code', $recovery)->call('login')->assertHasNoErrors()->assertRedirect(route('owner.dashboard', absolute: false));
        $this->assertAuthenticatedAs($this->owner);
        $this->assertNotContains($recovery, json_decode($this->owner->fresh()->recovery_codes, true));
    }

    public function test_guest_form_steps_validate_each_page()
    {
        $service = Service::first();

        Livewire::test(GuestBookingForm::class)
            ->call('nextStep')->assertSet('showErrorModal', true)->assertSet('currentStep', 1)
            ->set('showErrorModal', false)
            ->set('selectedServices', [$service->id])->call('nextStep')->assertSet('currentStep', 2)
            ->call('nextStep')->assertHasErrors(['vehicleMake', 'vehiclePlateNumber'])->assertSet('currentStep', 2)
            ->set('vehicleMake', 'Honda')->set('vehicleModel', 'City')->set('vehicleYear', 2019)->set('vehiclePlateNumber', 'GST 9')
            ->call('nextStep')->assertSet('currentStep', 3)
            ->set('customerName', 'Juan 123')->set('customerEmail', 'juan@test.com')->set('customerPhone', '0917abc')
            ->call('nextStep')->assertHasErrors(['customerName', 'customerPhone'])
            ->set('customerName', 'Juan Dela Cruz')->set('customerPhone', '09171234567')
            ->call('nextStep')->assertSet('currentStep', 4)
            ->call('previousStep')->assertSet('currentStep', 3);
    }

    public function test_guest_confirmation_page()
    {
        $this->get('/booking/confirmation/' . $this->booking->booking_reference)->assertOk()->assertSee($this->booking->booking_reference);
        $this->get('/booking/confirmation/BKNOTREAL00')->assertNotFound();
    }

    public function test_owner_calendar_bookings_history_and_reports()
    {
        Livewire::actingAs($this->owner);

        Livewire::test(BookingCalendar::class)
            ->call('nextMonth')->call('previousMonth')->call('today')
            ->set('statusFilter', 'approved')->assertOk();

        Livewire::test(OwnerBookings::class)
            ->set('statusFilter', 'advance')->set('bookingDate', now()->addDay()->toDateString())
            ->call('sortByColumn', 'total_amount')->call('sortByColumn', 'drop table')
            ->assertSet('sortBy', 'total_amount')
            ->call('clearFilters')->assertSet('statusFilter', '');

        Livewire::test(BookingHistory::class)
            ->set('statusFilter', 'completed')->call('clearFilters')->assertSet('dateFrom', '');

        Livewire::test(RevenueReport::class)
            ->set('from', now()->toDateString())->set('to', now()->subMonth()->toDateString()) // reversed on purpose
            ->assertSet('period', 'custom')->assertOk()
            ->set('period', 'this_year')->assertOk();

        foreach (['dashboard', 'today', 'calendar', 'bookings', 'history', 'reports', 'profile'] as $page) {
            $this->actingAs($this->owner)->get('/owner/' . $page)->assertOk();
        }
        $this->get('/owner/booking/' . $this->booking->id)->assertOk();
        $this->get('/owner/invoice/' . $this->booking->id)->assertOk();
    }

    public function test_owner_and_admin_can_update_their_profiles()
    {
        foreach ([[$this->owner, \App\Livewire\BusinessOwner\Profile::class], [$this->admin, \App\Livewire\Admin\Profile::class]] as [$user, $component]) {
            Livewire::actingAs($user)->test($component)
                ->set('name', 'Renamed ' . $user->id)->call('updateProfile')->assertHasNoErrors();
            $this->assertSame('Renamed ' . $user->id, $user->fresh()->name);
        }
    }

    public function test_admin_booking_filters()
    {
        Livewire::actingAs($this->admin)->test(AdminBookings::class)
            ->set('dateFrom', now()->toDateString())->set('dateTo', now()->addWeek()->toDateString())
            ->set('statusFilter', 'approved')->call('clearFilters')->assertSet('dateFrom', '')->assertOk();

        foreach (['dashboard', 'users', 'services', 'bookings', 'monitoring', 'profile'] as $page) {
            $this->actingAs($this->admin)->get('/admin/' . $page)->assertOk();
        }
        $this->get('/admin/booking/' . $this->booking->id)->assertOk();
    }
}
