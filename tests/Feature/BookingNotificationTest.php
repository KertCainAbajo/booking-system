<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\BookingUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class BookingNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected User $owner;
    protected Customer $profile;
    protected Vehicle $car;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->customerUser = User::factory()->create(['email' => 'cust@test.com', 'role_id' => Role::where('name', 'customer')->value('id')]);
        $this->owner = User::factory()->create(['email' => 'owner@test.com', 'role_id' => Role::where('name', 'business_owner')->value('id')]);
        $this->profile = Customer::create(['user_id' => $this->customerUser->id]);
        $this->car = Vehicle::create(['customer_id' => $this->profile->id, 'make' => 'Toyota', 'model' => 'Vios', 'year' => 2020, 'plate_number' => 'NTF 1']);
    }

    protected function book(array $extra = []): Booking
    {
        return Booking::create($extra + [
            'customer_id' => $this->profile->id, 'vehicle_id' => $this->car->id,
            'booking_date' => now()->addDays(2), 'booking_time' => '10:00', 'status' => 'pending', 'total_amount' => 1500,
        ]);
    }

    protected function event(string $event): \Closure
    {
        return fn (BookingUpdate $n) => $n->event === $event;
    }

    public function test_new_booking_tells_customer_and_owner()
    {
        Notification::fake();
        $this->actingAs($this->customerUser);

        $this->book();

        Notification::assertSentTo($this->customerUser, BookingUpdate::class, $this->event('received'));
        Notification::assertSentTo($this->owner, BookingUpdate::class, function (BookingUpdate $n) {
            return $n->event === 'new_booking' && str_contains($n->content['body'], 'Toyota Vios');
        });
    }

    public function test_owner_approving_tells_the_customer_on_every_channel()
    {
        $booking = $this->book();
        Notification::fake();
        $this->actingAs($this->owner);

        $booking->updateStatus('approved', $this->owner->id);

        Notification::assertSentTo($this->customerUser, BookingUpdate::class, function (BookingUpdate $n) {
            return $n->event === 'approved' && $n->channels === ['mail', 'database', 'push'] && $n->url === route('customer.tracker');
        });
        Notification::assertNotSentTo($this->owner, BookingUpdate::class); // they did it themselves
    }

    public function test_each_status_has_a_customer_message()
    {
        $booking = $this->book();
        $this->actingAs($this->owner);

        foreach (['approved', 'not_available', 'cancelled', 'completed', 'no_show'] as $status) {
            Notification::fake();
            $booking->updateStatus($status, $this->owner->id);
            Notification::assertSentTo($this->customerUser, BookingUpdate::class, $this->event($status));
        }
    }

    public function test_customer_cancelling_tells_the_owner_without_pushing_the_customer()
    {
        $booking = $this->book(['status' => 'approved']);
        Notification::fake();
        $this->actingAs($this->customerUser);

        $booking->updateStatus('cancelled', $this->customerUser->id);

        Notification::assertSentTo($this->owner, BookingUpdate::class, $this->event('customer_cancelled'));
        Notification::assertSentTo($this->customerUser, BookingUpdate::class, fn ($n) => $n->event === 'cancelled' && ! in_array('push', $n->channels, true));
    }

    public function test_mechanic_and_running_late_updates()
    {
        $booking = $this->book(['status' => 'approved']);
        Notification::fake();

        $this->actingAs($this->owner);
        $booking->update(['mechanic_name' => 'Mang Jun', 'mechanic_phone' => '0917']);
        Notification::assertSentTo($this->customerUser, BookingUpdate::class, fn ($n) => $n->event === 'mechanic' && str_contains($n->content['body'], 'Mang Jun'));

        $this->actingAs($this->customerUser);
        $booking->update(['marked_as_late' => true, 'estimated_arrival_time' => '10:30', 'late_reason' => 'Traffic']);
        Notification::assertSentTo($this->owner, BookingUpdate::class, fn ($n) => $n->event === 'running_late' && str_contains($n->content['body'], 'Traffic'));
    }

    public function test_guest_gets_email_with_tracker_link()
    {
        $guest = Customer::create(['name' => 'Juan', 'email' => 'juan@test.com', 'phone' => '0917', 'is_guest' => true]);
        $car = Vehicle::create(['customer_id' => $guest->id, 'make' => 'Honda', 'model' => 'City', 'year' => 2019, 'plate_number' => 'GST 1']);
        Notification::fake();

        $booking = Booking::create(['customer_id' => $guest->id, 'vehicle_id' => $car->id, 'booking_date' => now()->addDay(), 'booking_time' => '09:00', 'status' => 'pending', 'total_amount' => 0]);

        Notification::assertSentTo(new AnonymousNotifiable, BookingUpdate::class, function ($n, $channels, $notifiable) use ($booking) {
            return $notifiable->routes['mail'] === 'juan@test.com' && $channels === ['mail']
                && str_contains($n->url, $booking->booking_reference);
        });
    }

    public function test_reminder_is_sent_once_for_tomorrows_confirmed_bookings()
    {
        $tomorrow = $this->book(['status' => 'approved', 'booking_date' => now()->addDay()]);
        $this->book(['status' => 'pending', 'booking_date' => now()->addDay()]);
        Notification::fake();

        $this->artisan('bookings:remind')->assertSuccessful();
        $this->artisan('bookings:remind')->assertSuccessful();

        Notification::assertSentToTimes($this->customerUser, BookingUpdate::class, 1);
        $this->assertNotNull($tomorrow->fresh()->reminder_sent_at);
    }

    public function test_emails_and_bell_are_really_delivered()
    {
        $booking = $this->book();
        $this->actingAs($this->owner);
        $booking->updateStatus('approved', $this->owner->id);

        $note = $this->customerUser->notifications()->where('data->event', 'approved')->first();
        $this->assertNotNull($note);
        $this->assertSame('Booking confirmed', $note->data['title']);

        // The bell shows it, and opening it marks it read and goes to the tracker
        Livewire::actingAs($this->customerUser)->test(\App\Livewire\NotificationBell::class)
            ->assertSee('Booking confirmed')
            ->call('open', $note->id)
            ->assertRedirect(route('customer.tracker'));
        $this->assertNotNull($note->fresh()->read_at);
    }

    public function test_device_subscription_is_saved_and_removed_on_logout()
    {
        $this->actingAs($this->customerUser)->postJson('/push/subscribe', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/abc',
            'keys' => ['p256dh' => 'key', 'auth' => 'token'],
            'contentEncoding' => 'aes128gcm',
        ])->assertOk();
        $this->assertSame(1, $this->customerUser->pushSubscriptions()->count());

        $this->post('/logout');
        $this->assertSame(0, $this->customerUser->pushSubscriptions()->count());
    }

    public function test_push_endpoint_must_be_https()
    {
        $this->actingAs($this->customerUser)->postJson('/push/subscribe', [
            'endpoint' => 'http://evil.test/x', 'keys' => ['p256dh' => 'k', 'auth' => 't'],
        ])->assertStatus(422);
    }
}
