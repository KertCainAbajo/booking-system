<?php

namespace Tests\Feature;

use App\Livewire\Guest\BookingTracker;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_pages_send_security_headers()
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy');
        $this->assertStringContainsString("frame-ancestors 'none'", $response->headers->get('Content-Security-Policy'));
        $this->assertFalse($response->headers->has('X-Powered-By'));
    }

    public function test_logout_only_works_by_post()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/logout')->assertStatus(405);
        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_api_login_requires_the_two_factor_code()
    {
        $secret = (new Google2FA())->generateSecretKey();
        User::factory()->create([
            'email' => 'owner@test.com',
            'role_id' => Role::where('name', 'business_owner')->value('id'),
            'google2fa_enabled' => true,
            'google2fa_secret' => $secret,
        ]);

        $this->postJson('/api/v1/login', ['email' => 'owner@test.com', 'password' => 'password'])
            ->assertStatus(401)->assertJson(['requires_2fa' => true]);

        $this->postJson('/api/v1/login', ['email' => 'owner@test.com', 'password' => 'password', 'otp_code' => '000000'])
            ->assertStatus(401);

        $this->postJson('/api/v1/login', [
            'email' => 'owner@test.com', 'password' => 'password', 'otp_code' => (new Google2FA())->getCurrentOtp($secret),
        ])->assertOk()->assertJsonStructure(['data' => ['token']]);
    }

    public function test_guest_tracker_blocks_reference_guessing()
    {
        $tracker = Livewire::test(BookingTracker::class);
        for ($i = 0; $i < 10; $i++) {
            $tracker->set('bookingReference', 'BKGUESS' . $i)->call('trackBooking')->assertSet('notFound', true);
        }

        // The 11th lookup in a minute is refused before touching the database
        $tracker->set('bookingReference', 'BKGUESS99')->call('trackBooking')->assertSet('notFound', false);
    }

    public function test_forgot_password_does_not_reveal_who_has_an_account()
    {
        Volt::test('pages.auth.forgot-password')
            ->set('email', 'nobody@nowhere.test')
            ->call('sendPasswordResetLink')
            ->assertHasNoErrors()
            ->assertSee('If that email has an account');
    }

    public function test_weak_passwords_are_refused_at_registration()
    {
        Volt::test('pages.auth.register')
            ->set('name', 'Weak Person')->set('email', 'weak@test.com')
            ->set('password', 'password')->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors('password');

        $this->assertGuest();
    }

    public function test_booking_detail_id_cannot_be_changed_from_the_browser()
    {
        $owner = User::factory()->create(['role_id' => Role::where('name', 'business_owner')->value('id')]);
        $customer = \App\Models\Customer::create(['name' => 'X', 'email' => 'x@test.com', 'phone' => '1', 'is_guest' => true]);
        $vehicle = \App\Models\Vehicle::create(['customer_id' => $customer->id, 'make' => 'A', 'model' => 'B', 'year' => 2020, 'plate_number' => 'LCK 1']);
        $booking = \App\Models\Booking::create(['customer_id' => $customer->id, 'vehicle_id' => $vehicle->id, 'booking_date' => now()->addDay(), 'booking_time' => '09:00', 'status' => 'pending', 'total_amount' => 0]);

        $this->expectException(\Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException::class);

        Livewire::actingAs($owner)->test(\App\Livewire\Staff\BookingDetail::class, ['id' => $booking->id])
            ->set('bookingId', 999);
    }
}
