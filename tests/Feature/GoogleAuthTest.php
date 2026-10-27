<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as GoogleUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    protected function fakeGoogle(string $email, string $id = 'g-123', bool $verified = true): void
    {
        $googleUser = (new GoogleUser())->setRaw(['email_verified' => $verified])->map([
            'id' => $id, 'name' => 'Google Person', 'email' => $email,
        ]);

        $driver = Mockery::mock(\Laravel\Socialite\Two\GoogleProvider::class);
        $driver->shouldReceive('user')->andReturn($googleUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);
    }

    protected function userWithRole(string $role, array $extra = []): User
    {
        return User::factory()->create(['email' => $role . '@test.com', 'role_id' => Role::where('name', $role)->value('id')] + $extra);
    }

    public function test_new_google_user_becomes_a_customer()
    {
        $this->fakeGoogle('new@test.com');

        $this->get('/auth/google/callback')->assertRedirect(route('customer.dashboard'));

        $user = User::where('email', 'new@test.com')->first();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('customer', $user->role->name);
        $this->assertNotNull($user->customer);
        $this->assertSame('g-123', $user->google_id);
    }

    public function test_owner_and_admin_cannot_use_google_and_keep_their_role()
    {
        foreach (['business_owner', 'super_admin'] as $role) {
            $user = $this->userWithRole($role);
            $this->fakeGoogle($user->email);

            $this->get('/auth/google/callback')->assertRedirect(route('login'));

            $this->assertGuest();
            $this->assertSame($role, $user->fresh()->role->name);
        }
    }

    public function test_two_factor_account_must_use_password_and_code()
    {
        $user = $this->userWithRole('customer', ['google2fa_enabled' => true, 'google2fa_secret' => 'SECRET']);
        $this->fakeGoogle($user->email);

        $this->get('/auth/google/callback')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_unverified_google_email_is_refused()
    {
        $user = $this->userWithRole('customer');
        $this->fakeGoogle($user->email, verified: false);

        $this->get('/auth/google/callback')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_email_linked_to_another_google_account_is_refused()
    {
        $user = $this->userWithRole('customer', ['google_id' => 'g-original']);
        $this->fakeGoogle($user->email, 'g-attacker');

        $this->get('/auth/google/callback')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_expired_state_is_handled()
    {
        $driver = Mockery::mock(\Laravel\Socialite\Two\GoogleProvider::class);
        $driver->shouldReceive('user')->andThrow(new InvalidStateException());
        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $this->get('/auth/google/callback')->assertRedirect(route('login'))->assertSessionHasErrors('form.email');
        $this->assertGuest();
    }
}
