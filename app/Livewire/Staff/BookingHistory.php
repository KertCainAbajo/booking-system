<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Booking;

#[Layout('layouts.staff')]
class BookingHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortBy = 'archived_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        // Set default date range to last 30 days
        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
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
            ->where('is_archived', true);

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('customer.user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('vehicle', function($vehicleQuery) {
                    $vehicleQuery->where('registration_number', 'like', '%' . $this->search . '%')
                        ->orWhere('make', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                })
                ->orWhere('booking_reference', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->whereDate('archived_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('archived_at', '<=', $this->dateTo);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $bookings = $query->paginate(15);

        // Get statistics
        $stats = [
            'total' => Booking::where('is_archived', true)->count(),
            'completed' => Booking::where('is_archived', true)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('is_archived', true)->where('status', 'cancelled')->count(),
        ];

        return view('livewire.staff.booking-history', [
            'bookings' => $bookings,
            'stats' => $stats,
        ]);
    }
}

