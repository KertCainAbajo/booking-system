<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

/** The pop-up alerts for sign-in, sign-out and other key steps. */
class ToastAlertTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    protected function owner(): User
    {
        return User::factory()->create(['name' => 'Dexter Abajo', 'email' => 'owner@test.com', 'role_id' => Role::where('name', 'business_owner')->value('id')]);
    }

    public function test_signing_in_welcomes_the_user_by_first_name()
    {
        $this->owner();

        Volt::test('pages.auth.login')
            ->set('form.email', 'owner@test.com')->set('form.password', 'password')
            ->call('login');

        $this->assertSame('Welcome back, Dexter! You are signed in.', session('toast')['message']);
        $this->get('/owner/dashboard')->assertSee('Welcome back, Dexter!', false);
    }

    public function test_signing_out_says_so_on_the_login_page()
    {
        $this->actingAs($this->owner())->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();

        // (the apostrophe is escaped inside the page's script data, so match the rest)
        $this->get(route('login'))->assertSee('been signed out. See you next time!', false);
    }

    public function test_signing_out_from_the_profile_page_goes_straight_to_login_with_the_message()
    {
        \Livewire\Livewire::actingAs($this->owner())->test(\App\Livewire\BusinessOwner\Profile::class)
            ->call('logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertSame("You've been signed out. See you next time!", session('toast')['message']);
    }

    public function test_members_only_page_while_signed_out_asks_to_sign_in()
    {
        $this->get('/owner/dashboard')->assertRedirect(route('login'));

        $this->get(route('login'))->assertSee('Please sign in to continue.', false);
    }

    public function test_new_account_is_welcomed()
    {
        Volt::test('pages.auth.register')
            ->set('name', 'Juan Dela Cruz')->set('email', 'juan@test.com')
            ->set('password', 'Garage2026')->set('password_confirmation', 'Garage2026')
            ->call('register');

        $this->assertStringContainsString('Welcome to Dexter Auto, Juan!', session('toast')['message']);
    }

    public function test_no_permission_shows_an_error_alert()
    {
        $customer = User::factory()->create(['role_id' => Role::where('name', 'customer')->value('id')]);
        \App\Models\Customer::create(['user_id' => $customer->id]);

        $this->actingAs($customer)->get('/admin/dashboard')->assertRedirect(route('customer.dashboard'));
        $this->get(route('customer.dashboard'))->assertSee('You do not have permission to access that page.', false);
    }
}
