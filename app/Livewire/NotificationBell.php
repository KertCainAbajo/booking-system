<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/** The bell in the customer and owner layouts: latest booking updates and the "turn on alerts" switch. */
class NotificationBell extends Component
{
    public function open(string $id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if (! $notification) {
            return;
        }

        $notification->markAsRead();
        $url = $notification->data['url'] ?? null;

        // Only follow links back into this site
        if ($url && str_starts_with($url, url('/'))) {
            return $this->redirect($url);
        }
    }

    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.notification-bell', [
            'notifications' => $user->notifications()->latest()->limit(12)->get(),
            'unread' => $user->unreadNotifications()->count(),
        ]);
    }
}
