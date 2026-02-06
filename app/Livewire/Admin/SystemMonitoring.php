<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin')]
class SystemMonitoring extends Component
{
    public function render()
    {
        $stats = [
            'total_users' => User::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_services' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
        ];

        $recentBookings = Booking::with(['customer.user', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get();

        $recentUsers = User::with('role')
            ->latest()
            ->limit(5)
            ->get();

        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('livewire.admin.system-monitoring', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'recentUsers' => $recentUsers,
            'bookingsByStatus' => $bookingsByStatus,
        ]);
    }
}
