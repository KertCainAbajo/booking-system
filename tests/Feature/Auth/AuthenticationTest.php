<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    protected function userWithRole(string $role): User
    {
        $user = User::factory()->create(['role_id' => Role::where('name', $role)->value('id')]);

        if ($role === 'customer') {
            Customer::create(['user_id' => $user->id]);
        }

        return $user;
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login');
    }

    /** Each role lands on its own dashboard after signing in. */
    public function test_users_are_sent_to_their_own_dashboard_after_login(): void
    {
        foreach (['customer' => 'customer.dashboard', 'business_owner' => 'owner.dashboard', 'super_admin' => 'admin.dashboard'] as $role => $route) {
            $user = $this->userWithRole($role);

            Volt::test('pages.auth.login')
                ->set('form.email', $user->email)
                ->set('form.password', 'password')
                ->call('login')
                ->assertHasNoErrors()
                ->assertRedirect(route($route, absolute: false));

            $this->assertAuthenticatedAs($user);
            auth()->logout();
        }
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = $this->userWithRole('customer');

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_dashboard_link_redirects_to_the_users_own_dashboard(): void
    {
        $this->actingAs($this->userWithRole('business_owner'))
            ->get('/dashboard')
            ->assertRedirect(route('owner.dashboard'));
    }

    public function test_users_can_logout(): void
    {
        $this->actingAs($this->userWithRole('customer'))
            ->post(route('logout'))
            ->assertRedirect();

        $this->assertGuest();
    }
}
