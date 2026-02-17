<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\InventoryItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

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

        $totalRevenue = $completedBookingsRevenue + $paidPaymentsRevenue;
        
        // Date ranges for comparisons
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

        // Current month statistics
        $currentMonthBookings = Booking::where('created_at', '>=', $startOfMonth)->count();
        $lastMonthBookings = Booking::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $bookingsGrowth = $lastMonthBookings > 0 
            ? (($currentMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100 
            : 0;

        // Current month revenue
        $currentMonthRevenue = Booking::where('status', 'completed')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');
        $lastMonthRevenue = Booking::where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // New users this month
        $newUsersThisMonth = User::where('created_at', '>=', $startOfMonth)->count();
        $newUsersLastMonth = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $usersGrowth = $newUsersLastMonth > 0 
            ? (($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100 
            : 0;

        // Bookings by status
        $bookingsByStatus = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'no_show' => Booking::where('status', 'no_show')->count(),
        ];

        // Average booking value
        $averageBookingValue = Booking::where('status', 'completed')->avg('total_amount') ?? 0;

        // Top 5 services by booking count
        $topServices = DB::table('booking_services')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('COUNT(*) as booking_count'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();

        // This week vs last week
        $thisWeekBookings = Booking::where('created_at', '>=', $startOfWeek)->count();
        $lastWeekBookings = Booking::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $weeklyGrowth = $lastWeekBookings > 0 
            ? (($thisWeekBookings - $lastWeekBookings) / $lastWeekBookings) * 100 
            : 0;

        // Low inventory alert (items where quantity_in_stock is below or equal to reorder_level)
        $lowInventoryCount = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->count();

        $stats = [
            'total_users' => User::count(),
            'total_bookings' => Booking::count(),
            'total_services' => Service::count(),
            'total_inventory' => InventoryItem::count(),
            'total_revenue' => $totalRevenue,
            'average_booking_value' => $averageBookingValue,
            'new_users_this_month' => $newUsersThisMonth,
            'current_month_bookings' => $currentMonthBookings,
            'current_month_revenue' => $currentMonthRevenue,
            'bookings_growth' => $bookingsGrowth,
            'revenue_growth' => $revenueGrowth,
            'users_growth' => $usersGrowth,
            'weekly_growth' => $weeklyGrowth,
            'low_inventory_count' => $lowInventoryCount,
            'this_week_bookings' => $thisWeekBookings,
            'last_week_bookings' => $lastWeekBookings,
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
            'bookingsByStatus' => $bookingsByStatus,
            'topServices' => $topServices,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
