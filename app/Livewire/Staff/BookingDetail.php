<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Concerns\ManagesMechanic;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.owner')]
class BookingDetail extends Component
{
    use ManagesMechanic;

    /**
     * @var \App\Models\Booking
     */
    public $booking;
    public $bookingId;
    public $notes = '';

    public function mount($id)
    {
        $this->bookingId = $id;
        $this->loadBooking();
        $this->fillMechanic();
    }

    public function loadBooking()
    {
        $this->booking = Booking::with([
            'customer.user',
            'vehicle',
            'services',
            'payment',
            'statusLogs.user'
        ])->findOrFail($this->bookingId);
    }

    public function updateStatus($status)
    {
        // Only real statuses, and only when it actually changes
        if (! in_array($status, ['pending', 'approved', 'completed', 'cancelled', 'no_show', 'not_available'], true) || $status === $this->booking->status) {
            return;
        }

        $oldStatus = $this->booking->status;
        $this->booking->update(['status' => $status]);

        // Log status change
        /** @var \App\Models\User $user */
        $user = Auth::user();
        BookingStatusLog::create([
            'booking_id' => $this->booking->id,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'changed_by' => $user->id,
            'notes' => $this->notes,
        ]);

        // Broadcast event to update staff dashboard in real-time
        $this->dispatch('booking-status-changed')->to(\App\Livewire\Staff\Dashboard::class);

        $this->notes = '';
        $this->loadBooking();
        session()->flash('success', 'Booking status updated successfully!');
    }

    public function render()
    {
        return view('livewire.staff.booking-detail');
    }
}
