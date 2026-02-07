<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.staff')]
class BookingDetail extends Component
{
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
    }

    public function loadBooking()
    {
        $this->booking = Booking::with([
            'customer.user',
            'vehicle',
            'services',
            'assignedStaff',
            'payment',
            'statusLogs.user'
        ])->findOrFail($this->bookingId);
    }

    public function updateStatus($status)
    {
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

        $this->notes = '';
        $this->loadBooking();
        session()->flash('success', 'Booking status updated successfully!');
    }

    public function assignToMe()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->booking->update(['assigned_staff_id' => $user->id]);
        $this->loadBooking();
        session()->flash('success', 'Booking assigned to you!');
    }

    public function render()
    {
        return view('livewire.staff.booking-detail');
    }
}
