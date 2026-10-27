<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * One booking event, delivered by email, in the website's bell, and as a phone/desktop push.
 * The wording is built by App\Support\BookingNotifier; this class only delivers it.
 */
class BookingUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array{title: string, body: string, lines?: list<string>, action?: string}  $content
     * @param  list<string>  $channels  any of: mail, database, push
     */
    public function __construct(
        public int $bookingId,
        public string $event,
        public array $content,
        public string $url,
        public array $channels = ['mail', 'database', 'push'],
    ) {
        // Wait until the booking is fully saved (services attached, transaction committed)
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        $via = [];
        $email = $notifiable instanceof User ? $notifiable->email : $notifiable->routeNotificationFor('mail');

        if (in_array('mail', $this->channels, true) && $email) {
            $via[] = 'mail';
        }
        if ($notifiable instanceof User) {
            if (in_array('database', $this->channels, true)) {
                $via[] = 'database';
            }
            if (in_array('push', $this->channels, true) && $notifiable->pushSubscriptions()->exists()) {
                $via[] = WebPushChannel::class;
            }
        }

        return $via;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->content['title'] . ' – ' . config('app.name'))
            ->greeting($this->content['title'])
            ->line($this->content['body']);

        foreach ($this->content['lines'] ?? [] as $line) {
            $mail->line($line);
        }

        return $mail->action($this->content['action'] ?? 'View booking', $this->url)
            ->salutation('Thank you, ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->bookingId,
            'event' => $this->event,
            'title' => $this->content['title'],
            'body' => $this->content['body'],
            'url' => $this->url,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($this->content['title'])
            ->body($this->content['body'])
            ->icon(asset('images/shop.png'))
            ->badge(asset('images/shop.png'))
            ->tag('booking-' . $this->bookingId) // a newer update about the same booking replaces the old one
            ->renotify()
            ->data(['url' => $this->url])
            ->options(['TTL' => 86400, 'urgency' => 'high']);
    }
}
