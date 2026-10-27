<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** Every role has its own profile page. */
    public function test_each_role_can_open_its_profile_page(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        foreach (['customer' => '/customer/profile', 'business_owner' => '/owner/profile', 'super_admin' => '/admin/profile'] as $role => $path) {
            $user = User::factory()->create(['role_id' => \App\Models\Role::where('name', $role)->value('id')]);
            if ($role === 'customer') {
                \App\Models\Customer::create(['user_id' => $user->id]);
            }

            $this->actingAs($user)->get($path)->assertOk()->assertSee($user->email);
        }
    }

    public function test_customer_can_update_their_profile_and_password(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $user = User::factory()->create(['role_id' => \App\Models\Role::where('name', 'customer')->value('id')]);
        \App\Models\Customer::create(['user_id' => $user->id]);

        \Livewire\Livewire::actingAs($user)->test(\App\Livewire\Customer\Profile::class)
            ->set('name', 'New Name')
            ->set('phone', '09170001111')
            ->call('updateProfile')
            ->assertHasNoErrors()
            ->set('current_password', 'password')
            ->set('password', 'new-secret-123')
            ->set('password_confirmation', 'new-secret-123')
            ->call('updatePassword')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-secret-123', $user->password));
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.update-profile-information-form')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->call('updateProfileInformation');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.update-profile-information-form')
            ->set('name', 'Test User')
            ->set('email', $user->email)
            ->call('updateProfileInformation');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.delete-user-form')
            ->set('password', 'password')
            ->call('deleteUser');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.delete-user-form')
            ->set('password', 'wrong-password')
            ->call('deleteUser');

        $component
            ->assertHasErrors('password')
            ->assertNoRedirect();

        $this->assertNotNull($user->fresh());
    }
}
