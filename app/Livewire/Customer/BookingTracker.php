<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.customer')]
class BookingTracker extends Component
{
    public $showLateModal = false;
    public $selectedBookingId = null;
    public $lateReason = '';
    public $estimatedArrivalTime = '';
    public function cancelBooking($bookingId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->relationLoaded('customer')) {
            $user->load('customer');
        }
        
        $customer = $user->customer;
        
        if (!$customer instanceof Customer) {
            abort(403, 'Customer profile not found');
        }

        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if (!$booking) {
            session()->flash('error', 'Booking not found or cannot be cancelled.');
            return;
        }

        // Store old status before updating
        $oldStatus = $booking->status;
        
        $booking->status = 'cancelled';
        $booking->save();

        // Log the status change
        $booking->statusLogs()->create([
            'old_status' => $oldStatus,
            'new_status' => 'cancelled',
            'changed_by' => Auth::id(),
            'notes' => 'Cancelled by customer'
        ]);

        session()->flash('success', 'Booking has been cancelled successfully.');
    }

    public function openLateModal($bookingId)
    {
        $this->selectedBookingId = $bookingId;
        $this->showLateModal = true;
        $this->lateReason = '';
        $this->estimatedArrivalTime = now()->addMinutes(15)->format('H:i');
    }

    public function closeLateModal()
    {
        $this->showLateModal = false;
        $this->selectedBookingId = null;
        $this->lateReason = '';
        $this->estimatedArrivalTime = '';
    }

    public function markAsLate()
    {
        $this->validate([
            'lateReason' => 'nullable|string|max:500',
            'estimatedArrivalTime' => 'required',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->relationLoaded('customer')) {
            $user->load('customer');
        }
        
        $customer = $user->customer;
        
        if (!$customer instanceof Customer) {
            abort(403, 'Customer profile not found');
        }

        $booking = Booking::where('id', $this->selectedBookingId)
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if (!$booking) {
            session()->flash('error', 'Booking not found or cannot be marked as late.');
            $this->closeLateModal();
            return;
        }

        $booking->update([
            'marked_as_late' => true,
            'marked_late_at' => now(),
            'late_reason' => $this->lateReason ?: 'Customer notified they will be late',
            'estimated_arrival_time' => $this->estimatedArrivalTime,
        ]);

        // Log the status change
        $booking->statusLogs()->create([
            'old_status' => $booking->status,
            'new_status' => $booking->status,
            'changed_by' => Auth::id(),
            'notes' => 'Customer marked as late. Estimated arrival: ' . $this->estimatedArrivalTime . 
                       ($this->lateReason ? ' - Reason: ' . $this->lateReason : '')
        ]);

        session()->flash('success', 'Thank you for notifying us. We\'ve updated your service status.');
        $this->closeLateModal();
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->relationLoaded('customer')) {
            $user->load('customer');
        }
        
        $customer = $user->customer;
        
        if (!$customer instanceof Customer) {
            abort(403, 'Customer profile not found');
        }
        
        // Type assertion for static analyzer - $customer is guaranteed to be Customer at this point
        /** @var Customer $customer */
        
        $activeBookings = Booking::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'approved'])
            ->with(['vehicle', 'services', 'assignedStaff'])
            ->latest()
            ->get();

        return view('livewire.customer.booking-tracker', [
            'activeBookings' => $activeBookings,
        ]);
    }
}
