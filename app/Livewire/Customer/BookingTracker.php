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
    public function cancelBooking($bookingId)
    {
        $customer = Auth::user()->customer;
        
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

    public function render()
    {
        $customer = Auth::user()->customer;
        
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
