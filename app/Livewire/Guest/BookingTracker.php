<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use Illuminate\Support\Facades\RateLimiter;

#[Layout('layouts.app')]
class BookingTracker extends Component
{
    public $bookingReference;
    public $booking;
    public $notFound = false;

    public function mount()
    {
        if (request()->has('reference')) {
            $this->bookingReference = request('reference');
            $this->trackBooking();
        }
    }

    public function trackBooking()
    {
        $this->notFound = false;
        
        if (empty($this->bookingReference) || ! is_string($this->bookingReference)) {
            $this->notFound = true;
            return;
        }

        // Stop anyone guessing references: 10 lookups a minute per visitor
        $key = 'guest-track:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->booking = null;
            session()->flash('error', 'Too many lookups. Please wait ' . RateLimiter::availableIn($key) . ' seconds and try again.');
            return;
        }
        RateLimiter::hit($key, 60);

        $this->booking = Booking::with(['customer', 'vehicle', 'services', 'statusLogs'])
            ->where('booking_reference', strtoupper(trim(substr($this->bookingReference, 0, 20))))
            ->first();

        if (!$this->booking) {
            $this->notFound = true;
            return;
        }

        // If booking is completed, treat it as if reference is no longer valid
        if (!$this->booking->canShowReference()) {
            $this->booking = null;
            $this->notFound = true;
            session()->flash('error', 'This booking reference is no longer available. The service has been completed.');
        }
    }

    public function cancelBooking()
    {
        if (!$this->booking) {
            session()->flash('error', 'Booking not found.');
            return;
        }

        if (!in_array($this->booking->status, ['pending', 'approved'])) {
            session()->flash('error', 'This booking cannot be cancelled.');
            return;
        }

        // Store old status before updating
        $oldStatus = $this->booking->status;
        
        $this->booking->status = 'cancelled';
        $this->booking->save();

        // Log the status change
        BookingStatusLog::create([
            'booking_id' => $this->booking->id,
            'old_status' => $oldStatus,
            'new_status' => 'cancelled',
            'changed_by' => null, // Guest user
            'notes' => 'Cancelled by guest customer'
        ]);

        // Refresh booking data
        $this->trackBooking();
        
        session()->flash('success', 'Booking has been cancelled successfully.');
    }

    public function render()
    {
        return view('livewire.guest.booking-tracker');
    }
}
