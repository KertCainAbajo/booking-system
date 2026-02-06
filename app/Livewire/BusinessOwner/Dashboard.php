<?php

namespace App\Livewire\BusinessOwner;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.owner')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'today_bookings' => Booking::whereDate('booking_date', today())->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_today' => Booking::whereDate('booking_date', today())->where('status', 'completed')->count(),
            'today_revenue' => Payment::whereDate('created_at', today())->where('status', 'completed')->sum('amount'),
            'month_revenue' => Payment::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
        ];

        $recentBookings = Booking::with(['customer.user', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get();

        $topServices = Service::withCount('bookingServices')
            ->orderBy('booking_services_count', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.business-owner.dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'topServices' => $topServices,
        ]);
    }
}
