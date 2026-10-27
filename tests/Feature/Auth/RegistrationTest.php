<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    public function test_new_users_can_register(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'Garage2026')
            ->set('password_confirmation', 'Garage2026');

        $component->call('register');

        // New sign-ups are customers and land on the customer dashboard
        $component->assertRedirect(route('customer.dashboard', absolute: false));

        $this->assertAuthenticated();
        $user = \App\Models\User::where('email', 'test@example.com')->first();
        $this->assertSame('customer', $user->role->name);
        $this->assertNotNull($user->customer);
    }
}
