<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.staff')]
class Dashboard extends Component
{
    public $currentDate;
    public $lastUpdateTime;

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
        $this->lastUpdateTime = now()->format('H:i:s');
    }
    
    // Listen for booking events to refresh dashboard
    #[On('booking-created')]
    #[On('booking-updated')]
    #[On('booking-status-changed')]
    public function refreshDashboard()
    {
        $this->lastUpdateTime = now()->format('H:i:s');
        // The render method will automatically fetch fresh data
    }

    public function render()
    {
        // Clear any query cache to ensure fresh data
        DB::connection()->disableQueryLog();
        
        // Force model cache clearing
        Booking::flushEventListeners();
        Booking::clearBootedModels();
        
        // Always fetch fresh data from database for today
        $today = now()->toDateString(); // Use date string for consistent comparison
        $yesterday = now()->subDay()->toDateString();
        
        // Today's bookings - always query fresh from database, exclude archived
        // Use fresh() to bypass any Eloquent caching
        $todayBookingsList = Booking::with(['customer.user', 'vehicle', 'services'])
            ->where('is_archived', false)
            ->whereDate('booking_date', $today)
            ->orderBy('booking_time')
            ->get()
            ->fresh();

        // This week's stats - optimized single query
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();
        
        $weekBookingsCounts = Booking::selectRaw('status, COUNT(*) as count')
            ->where('is_archived', false)
            ->whereBetween('booking_date', [$weekStart, $weekEnd])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $weekStats = [
            'total' => array_sum($weekBookingsCounts),
            'pending' => $weekBookingsCounts['pending'] ?? 0,
            'approved' => $weekBookingsCounts['approved'] ?? 0,
            'completed' => $weekBookingsCounts['completed'] ?? 0,
            'cancelled' => $weekBookingsCounts['cancelled'] ?? 0,
        ];

        // Today's stats - optimized single query for status counts
        $todayBookingsCounts = Booking::selectRaw('status, COUNT(*) as count')
            ->where('is_archived', false)
            ->whereDate('booking_date', $today)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $todayStats = [
            'total' => array_sum($todayBookingsCounts),
            'pending' => $todayBookingsCounts['pending'] ?? 0,
            'approved' => $todayBookingsCounts['approved'] ?? 0,
            'completed' => $todayBookingsCounts['completed'] ?? 0,
            'cancelled' => $todayBookingsCounts['cancelled'] ?? 0,
        ];

        // Yesterday's stats for comparison
        $yesterdayStats = [
            'total' => Booking::where('is_archived', false)->whereDate('booking_date', $yesterday)->count(),
        ];

        // Statistical calculations
        $totalToday = $todayStats['total'];
        $totalYesterday = $yesterdayStats['total'];
        
        // Calculate percentages
        $todayStats['pending_percent'] = $totalToday > 0 ? round(($todayStats['pending'] / $totalToday) * 100, 1) : 0;
        $todayStats['approved_percent'] = $totalToday > 0 ? round(($todayStats['approved'] / $totalToday) * 100, 1) : 0;
        $todayStats['completed_percent'] = $totalToday > 0 ? round(($todayStats['completed'] / $totalToday) * 100, 1) : 0;
        $todayStats['cancelled_percent'] = $totalToday > 0 ? round(($todayStats['cancelled'] / $totalToday) * 100, 1) : 0;
        
        // Calculate comparison with yesterday
        if ($totalYesterday > 0) {
            $todayStats['change_percent'] = round((($totalToday - $totalYesterday) / $totalYesterday) * 100, 1);
            $todayStats['change_direction'] = $totalToday > $totalYesterday ? 'up' : ($totalToday < $totalYesterday ? 'down' : 'same');
        } else {
            $todayStats['change_percent'] = $totalToday > 0 ? 100 : 0;
            $todayStats['change_direction'] = $totalToday > 0 ? 'up' : 'same';
        }
        
        // Calculate success rate (completed + approved)
        $successCount = $todayStats['completed'] + $todayStats['approved'];
        $todayStats['success_rate'] = $totalToday > 0 ? round(($successCount / $totalToday) * 100, 1) : 0;
        
        // Calculate completion rate (completed only)
        $todayStats['completion_rate'] = $totalToday > 0 ? round(($todayStats['completed'] / $totalToday) * 100, 1) : 0;
        
        // Calculate average bookings per hour (business hours: 8am - 6pm = 10 hours)
        $currentHour = now()->hour;
        $businessHoursElapsed = max(1, min(10, $currentHour - 8 + 1)); // At least 1 hour
        $todayStats['avg_per_hour'] = $businessHoursElapsed > 0 ? round($totalToday / $businessHoursElapsed, 1) : 0;

        // Upcoming bookings (next 7 days), exclude archived
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->where('is_archived', false)
            ->whereBetween('booking_date', [now()->addDay()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->take(5)
            ->get();

        return view('livewire.staff.dashboard', [
            'todayBookings' => $todayBookingsList,
            'weekStats' => $weekStats,
            'todayStats' => $todayStats,
            'yesterdayTotal' => $totalYesterday,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
