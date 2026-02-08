<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.staff')]
class BookingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $bookingDate = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        // Set default date to today
        $this->bookingDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function viewBooking($id)
    {
        return redirect()->route('staff.booking.detail', $id);
    }

    public function render()
    {
        $query = Booking::with(['customer.user', 'vehicle', 'assignedStaff', 'services'])
            ->where('is_archived', false);

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('customer.user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('vehicle', function($vehicleQuery) {
                    $vehicleQuery->where('license_plate', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                })
                ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Date filter
        if ($this->bookingDate) {
            $query->whereDate('booking_date', $this->bookingDate);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $bookings = $query->paginate(15);

        // Get statistics
        $stats = [
            'total' => Booking::where('is_archived', false)->count(),
            'pending' => Booking::where('is_archived', false)->where('status', 'pending')->count(),
            'approved' => Booking::where('is_archived', false)->where('status', 'approved')->count(),
            'completed' => Booking::where('is_archived', false)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('is_archived', false)->where('status', 'cancelled')->count(),
        ];

        return view('livewire.staff.booking-management', [
            'bookings' => $bookings,
            'stats' => $stats,
        ]);
    }
}
