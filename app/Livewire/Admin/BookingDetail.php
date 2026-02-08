<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.admin')]
class BookingDetail extends Component
{
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
            'assignedStaff',
            'services',
            'payment',
            'statusLogs' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'statusLogs.user'
        ])->findOrFail($this->bookingId);
    }

    public function updateStatus($status)
    {
        $oldStatus = $this->booking->status;
        
        $this->booking->update(['status' => $status]);

        /** @var User $user */
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

    public function assignStaff($staffId)
    {
        $this->booking->update(['assigned_staff_id' => $staffId]);
        $this->loadBooking();
        session()->flash('success', 'Staff assigned successfully!');
    }

    public function render()
    {
        $availableStaff = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->get();

        return view('livewire.admin.booking-detail', [
            'availableStaff' => $availableStaff
        ]);
    }
}
