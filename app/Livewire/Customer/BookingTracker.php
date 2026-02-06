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
    public function render()
    {
        $customer = Auth::user()->customer;
        
        if (!$customer instanceof Customer) {
            abort(403, 'Customer profile not found');
        }
        
        // Type assertion for static analyzer - $customer is guaranteed to be Customer at this point
        /** @var Customer $customer */
        
        $activeBookings = Booking::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->with(['vehicle', 'services', 'assignedStaff'])
            ->latest()
            ->get();

        return view('livewire.customer.booking-tracker', [
            'activeBookings' => $activeBookings,
        ]);
    }
}
