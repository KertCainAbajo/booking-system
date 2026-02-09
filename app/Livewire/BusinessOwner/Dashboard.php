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
        // Calculate revenue from completed bookings (which includes all selected services)
        $todayCompletedRevenue = Booking::whereDate('booking_date', today())
            ->where('status', 'completed')
            ->sum('total_amount');
        
        $todayPaidPayments = Payment::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->whereHas('booking', function($query) {
                $query->where('status', '!=', 'completed')
                      ->orWhereDate('booking_date', '!=', today());
            })
            ->sum('amount');
        
        $monthCompletedRevenue = Booking::whereMonth('booking_date', now()->month)
            ->where('status', 'completed')
            ->sum('total_amount');
        
        $monthPaidPayments = Payment::whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')
            ->whereHas('booking', function($query) {
                $query->where('status', '!=', 'completed')
                      ->orWhereMonth('booking_date', '!=', now()->month);
            })
            ->sum('amount');

        $stats = [
            'today_bookings' => Booking::whereDate('booking_date', today())->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_today' => Booking::whereDate('booking_date', today())->where('status', 'completed')->count(),
            'today_revenue' => $todayCompletedRevenue + $todayPaidPayments,
            'month_revenue' => $monthCompletedRevenue + $monthPaidPayments,
            'pending_payments' => Payment::where('payment_status', 'pending')->sum('amount'),
        ];

        $recentBookings = Booking::with(['customer.user', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get();

        $topServices = Service::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Upcoming bookings (next 7 days), exclude archived
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->where('is_archived', false)
            ->whereBetween('booking_date', [now()->addDay(), now()->addDays(7)])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->take(5)
            ->get();

        return view('livewire.business-owner.dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'topServices' => $topServices,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
