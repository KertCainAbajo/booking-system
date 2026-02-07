<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;

#[Layout('layouts.admin')]
class BookingDetail extends Component
{
    public $booking;
    public $bookingId;

    public function mount($id)
    {
        $this->bookingId = $id;
        $this->booking = Booking::with([
            'customer.user',
            'vehicle',
            'assignedStaff',
            'services',
            'payment',
            'statusLogs' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.booking-detail');
    }
}
