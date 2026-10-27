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
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('customer', function($customerQuery) {
                    // Guest bookings keep the contact details on the customer record
                    $customerQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('vehicle', function($vehicleQuery) {
                    $vehicleQuery->where('plate_number', 'like', '%' . $this->search . '%')
                        ->orWhere('make', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                })
                ->orWhere('booking_reference', 'like', '%' . $this->search . '%')
                ->orWhere('id', 'like', '%' . $this->search . '%');
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
