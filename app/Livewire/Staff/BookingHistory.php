<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Booking;

#[Layout('layouts.owner')]
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if (! in_array($column, ['booking_date', 'archived_at', 'total_amount'], true)) {
            return;
        }

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function viewBooking($id)
    {
        return redirect()->route('owner.booking.detail', $id);
    }

    public function render()
    {
        $query = Booking::with(['customer.user', 'vehicle', 'services'])
            ->where('is_archived', true);

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('customer.user', function($userQuery) {
                    $userQuery->whereLike('name','%' . $this->search . '%')
                        ->orWhereLike('email','%' . $this->search . '%')
                        ->orWhereLike('phone','%' . $this->search . '%');
                })
                ->orWhereHas('customer', function($customerQuery) {
                    // Guest bookings keep the contact details on the customer record
                    $customerQuery->whereLike('name','%' . $this->search . '%')
                        ->orWhereLike('phone','%' . $this->search . '%');
                })
                ->orWhereHas('vehicle', function($vehicleQuery) {
                    $vehicleQuery->whereLike('plate_number','%' . $this->search . '%')
                        ->orWhereLike('make','%' . $this->search . '%')
                        ->orWhereLike('model','%' . $this->search . '%');
                })
                ->orWhereLike('booking_reference','%' . $this->search . '%');
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

