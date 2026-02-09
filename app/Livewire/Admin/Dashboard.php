<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\InventoryItem;
use App\Models\Payment;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        // Calculate total revenue from completed bookings (which includes all selected services)
        $completedBookingsRevenue = Booking::where('status', 'completed')->sum('total_amount');
        
        // Also include paid payments that might not be from completed bookings yet
        $paidPaymentsRevenue = Payment::where('payment_status', 'paid')
            ->whereHas('booking', function($query) {
                $query->where('status', '!=', 'completed'); // Avoid double counting
            })
            ->sum('amount');

        $stats = [
            'total_users' => User::count(),
            'total_bookings' => Booking::count(),
            'total_services' => Service::count(),
            'total_inventory' => InventoryItem::count(),
            'total_revenue' => $completedBookingsRevenue + $paidPaymentsRevenue,
        ];

        // Upcoming bookings (next 7 days), exclude archived
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->where('is_archived', false)
            ->whereBetween('booking_date', [now()->addDay(), now()->addDays(7)])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
