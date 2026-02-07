<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Carbon\Carbon;

#[Layout('layouts.staff')]
class Dashboard extends Component
{
    public function render()
    {
        // Today's bookings
        $todayBookings = Booking::with(['customer.user', 'vehicle', 'services'])
            ->whereDate('booking_date', today())
            ->orderBy('booking_time')
            ->get();

        // This week's stats
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        
        $weekStats = [
            'total' => Booking::whereBetween('booking_date', [$weekStart, $weekEnd])->count(),
            'pending' => Booking::whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'pending')->count(),
            'approved' => Booking::whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'approved')->count(),
            'completed' => Booking::whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'completed')->count(),
            'cancelled' => Booking::whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'cancelled')->count(),
        ];

        // Today's stats
        $todayStats = [
            'total' => Booking::whereDate('booking_date', today())->count(),
            'pending' => Booking::whereDate('booking_date', today())->where('status', 'pending')->count(),
            'approved' => Booking::whereDate('booking_date', today())->where('status', 'approved')->count(),
            'completed' => Booking::whereDate('booking_date', today())->where('status', 'completed')->count(),
            'cancelled' => Booking::whereDate('booking_date', today())->where('status', 'cancelled')->count(),
        ];

        // Upcoming bookings (next 7 days)
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->whereBetween('booking_date', [now()->addDay(), now()->addDays(7)])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->take(5)
            ->get();

        return view('livewire.staff.dashboard', [
            'todayBookings' => $todayBookings,
            'weekStats' => $weekStats,
            'todayStats' => $todayStats,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
