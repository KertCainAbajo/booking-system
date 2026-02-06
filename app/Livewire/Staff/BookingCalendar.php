<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Carbon\Carbon;

#[Layout('layouts.staff')]
class BookingCalendar extends Component
{
    public $selectedDate;
    public $statusFilter = 'all';

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Booking::with(['customer.user', 'vehicle', 'services'])
            ->whereDate('booking_date', $this->selectedDate);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $bookings = $query->orderBy('booking_time')->get();

        $stats = [
            'total' => Booking::whereDate('booking_date', $this->selectedDate)->count(),
            'pending' => Booking::whereDate('booking_date', $this->selectedDate)->where('status', 'pending')->count(),
            'confirmed' => Booking::whereDate('booking_date', $this->selectedDate)->where('status', 'confirmed')->count(),
            'in_progress' => Booking::whereDate('booking_date', $this->selectedDate)->where('status', 'in_progress')->count(),
            'completed' => Booking::whereDate('booking_date', $this->selectedDate)->where('status', 'completed')->count(),
        ];

        return view('livewire.staff.booking-calendar', [
            'bookings' => $bookings,
            'stats' => $stats,
        ]);
    }
}
