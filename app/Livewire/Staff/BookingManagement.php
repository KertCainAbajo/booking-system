<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.owner')]
class BookingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $bookingDate = '';
    public $sortBy = 'booking_date';
    public $sortDirection = 'asc';

    public function mount()
    {
        // Show all bookings by default (no date filter)
        $this->bookingDate = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingBookingDate()
    {
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

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->bookingDate = '';
        $this->resetPage();
    }

    public function viewBooking($id)
    {
        return redirect()->route('owner.booking.detail', $id);
    }

    public function render()
    {
        $query = Booking::with(['customer.user', 'vehicle', 'services'])
            ->where('is_archived', false);

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
                // Booking number: exact match (a number column can't be text-searched on PostgreSQL)
                ->when(ctype_digit((string) $this->search), fn ($q) => $q->orWhere('id', (int) $this->search));
            });
        }

        // Status filter
        if ($this->statusFilter) {
            if ($this->statusFilter === 'advance') {
                $query->where('booking_date', '>', now()->toDateString());
            } else {
                $query->where('status', $this->statusFilter);
            }
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
            'advance' => Booking::where('is_archived', false)->where('booking_date', '>', now()->toDateString())->count(),
        ];

        return view('livewire.staff.booking-management', [
            'bookings' => $bookings,
            'stats' => $stats,
        ]);
    }
}
