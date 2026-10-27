<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\InventoryItem;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Support\Revenue;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        $monthRevenue = Revenue::between($monthStart, $monthEnd);
        $lastMonthRevenue = Revenue::between($lastMonthStart, $lastMonthEnd);

        $monthBookings = Booking::whereBetween('booking_date', [$monthStart->toDateString(), $monthEnd->toDateString()])->count();
        $lastMonthBookings = Booking::whereBetween('booking_date', [$lastMonthStart->toDateString(), $lastMonthEnd->toDateString()])->count();

        $newUsers = User::where('created_at', '>=', $monthStart)->count();
        $newUsersLastMonth = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        $stats = [
            'total_users' => User::count(),
            'new_users' => $newUsers,
            'new_users_change' => Revenue::change($newUsers, $newUsersLastMonth),
            'month_bookings' => $monthBookings,
            'month_bookings_change' => Revenue::change($monthBookings, $lastMonthBookings),
            'month_revenue' => $monthRevenue,
            'month_revenue_change' => Revenue::change($monthRevenue, $lastMonthRevenue),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_services' => Service::where('is_active', true)->count(),
            'total_services' => Service::count(),
            'total_inventory' => InventoryItem::count(),
        ];

        $roles = Role::withCount('users')->get()->keyBy('name');

        $bookingsByStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $lowStock = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->orderBy('quantity_in_stock')
            ->limit(5)
            ->get();

        $recentActivity = BookingStatusLog::with(['user', 'booking.customer.user'])
            ->latest()
            ->limit(8)
            ->get();

        $recentUsers = User::with('role')->latest()->limit(5)->get();

        // Upcoming bookings (next 7 days), exclude archived
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->where('is_archived', false)
            ->whereBetween('booking_date', [now()->addDay()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'roles' => $roles,
            'bookingsByStatus' => $bookingsByStatus,
            'lowStock' => $lowStock,
            'recentActivity' => $recentActivity,
            'recentUsers' => $recentUsers,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
