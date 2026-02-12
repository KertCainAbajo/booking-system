<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.customer')]
class ServiceHistory extends Component
{
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
        
        /** @var Customer $customer */
        
        // Get all completed and cancelled bookings
        $historyBookings = Booking::where('customer_id', $customer->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['vehicle', 'services', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return view('livewire.customer.service-history', [
            'historyBookings' => $historyBookings,
        ]);
    }
}
