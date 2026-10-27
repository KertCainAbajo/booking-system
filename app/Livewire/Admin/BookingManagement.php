<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin')]
class BookingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

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
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if (! in_array($column, ['id', 'booking_date', 'created_at', 'total_amount'], true)) {
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
        return redirect()->route('admin.booking.detail', $id);
    }

    public function render()
    {
        $query = Booking::with(['customer.user', 'vehicle', 'services']);

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
                ->orWhereLike('booking_reference','%' . $this->search . '%')
                // Booking number: exact match (a number column can't be text-searched on PostgreSQL)
                ->when(ctype_digit((string) $this->search), fn ($q) => $q->orWhere('id', (int) $this->search));
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->where('booking_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->where('booking_date', '<=', $this->dateTo);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $bookings = $query->paginate(15);

        // Get statistics
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'archived' => Booking::where('is_archived', true)->count(),
        ];

        return view('livewire.admin.booking-management', [
            'bookings' => $bookings,
            'stats' => $stats,
        ]);
    }
}
