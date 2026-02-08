<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Carbon\Carbon;

#[Layout('layouts.staff')]
class Dashboard extends Component
{
    public $currentDate;

    public function mount()
    {
        // Check if it's a new day and clear session data if needed
        $lastVisitDate = session('staff_last_visit_date');
        $currentDate = today()->toDateString();
        
        if ($lastVisitDate !== $currentDate) {
            // It's a new day - clear any cached data
            session()->forget('staff_dashboard_data');
            session(['staff_last_visit_date' => $currentDate]);
        }
        
        $this->currentDate = $currentDate;
    }

    public function render()
    {
        // Always fetch fresh data from database for today
        $today = today();
        
        // Today's bookings - always query fresh from database, exclude archived
        $todayBookings = Booking::with(['customer.user', 'vehicle', 'services'])
            ->where('is_archived', false)
            ->whereDate('booking_date', $today)
            ->orderBy('booking_time')
            ->get();

        // This week's stats
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        
        $weekStats = [
            'total' => Booking::where('is_archived', false)->whereBetween('booking_date', [$weekStart, $weekEnd])->count(),
            'pending' => Booking::where('is_archived', false)->whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'pending')->count(),
            'approved' => Booking::where('is_archived', false)->whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'approved')->count(),
            'completed' => Booking::where('is_archived', false)->whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'completed')->count(),
            'cancelled' => Booking::where('is_archived', false)->whereBetween('booking_date', [$weekStart, $weekEnd])->where('status', 'cancelled')->count(),
        ];

        // Today's stats - always fresh count, exclude archived
        $todayStats = [
            'total' => Booking::where('is_archived', false)->whereDate('booking_date', $today)->count(),
            'pending' => Booking::where('is_archived', false)->whereDate('booking_date', $today)->where('status', 'pending')->count(),
            'approved' => Booking::where('is_archived', false)->whereDate('booking_date', $today)->where('status', 'approved')->count(),
            'completed' => Booking::where('is_archived', false)->whereDate('booking_date', $today)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('is_archived', false)->whereDate('booking_date', $today)->where('status', 'cancelled')->count(),
        ];

        // Upcoming bookings (next 7 days), exclude archived
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->where('is_archived', false)
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
