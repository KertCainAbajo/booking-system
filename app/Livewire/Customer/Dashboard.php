<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.customer')]
class Dashboard extends Component
{
    public function render()
    {
        // Get the authenticated user and eager load customer relationship if not already loaded
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->relationLoaded('customer')) {
            $user->load('customer');
        }
        
        $customer = $user->customer;
        
        if (!$customer instanceof Customer) {
            abort(403, 'Customer profile not found');
        }
        
        /** @var Customer $customer */
        
        // Get all bookings for the customer, ordered by most recent
        $allBookings = Booking::where('customer_id', $customer->id)
            ->with(['vehicle', 'services'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->limit(10) // Show last 10 bookings
            ->get();

        return view('livewire.customer.dashboard', [
            'allBookings' => $allBookings,
        ]);
    }
}
