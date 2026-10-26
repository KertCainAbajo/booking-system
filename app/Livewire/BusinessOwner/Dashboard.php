<?php

namespace App\Livewire\BusinessOwner;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Support\Revenue;

#[Layout('layouts.owner')]
class Dashboard extends Component
{
    public function render()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();
        $inactive = ['cancelled', 'no_show', 'not_available'];

        $monthRevenue = Revenue::between($monthStart, $monthEnd);
        $lastMonthRevenue = Revenue::between($lastMonthStart, $lastMonthEnd);

        $monthBookings = Booking::whereBetween('booking_date', [$monthStart->toDateString(), $monthEnd->toDateString()]);
        $monthTotal = (clone $monthBookings)->count();
        $monthCompleted = (clone $monthBookings)->where('status', 'completed')->count();
        $monthCancelled = (clone $monthBookings)->whereIn('status', $inactive)->count();
        $lastMonthTotal = Booking::whereBetween('booking_date', [$lastMonthStart->toDateString(), $lastMonthEnd->toDateString()])->count();

        $stats = [
            'today_revenue' => Revenue::between(today(), today()),
            'today_bookings' => Booking::whereDate('booking_date', today())->count(),
            'month_revenue' => $monthRevenue,
            'month_revenue_change' => Revenue::change($monthRevenue, $lastMonthRevenue),
            'month_bookings' => $monthTotal,
            'month_bookings_change' => Revenue::change($monthTotal, $lastMonthTotal),
            'month_completed' => $monthCompleted,
            'avg_job_value' => $monthCompleted > 0 ? $monthRevenue / $monthCompleted : 0,
            'cancel_rate' => $monthTotal > 0 ? round(($monthCancelled / $monthTotal) * 100) : 0,
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'pending_payments' => (float) Payment::where('payment_status', 'pending')->sum('amount'),
        ];

        // Revenue for the last 6 months (oldest first) for the chart
        $monthlyRevenue = collect(range(5, 0))->map(function ($monthsAgo) {
            $start = now()->subMonthsNoOverflow($monthsAgo)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            return [
                'label' => $start->format('M'),
                'full_label' => $start->format('F Y'),
                'revenue' => Revenue::between($start, $end),
                'jobs' => Booking::whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])->where('status', 'completed')->count(),
                'is_current' => $monthsAgo === 0,
            ];
        });

        $recentBookings = Booking::with(['customer.user', 'vehicle'])
            ->latest()
            ->limit(6)
            ->get();

        // Most booked services (ignoring cancelled bookings) and what they earned when completed
        $topServices = Service::with('category')
            ->withCount(['bookings as times_booked' => fn ($q) => $q->whereNotIn('status', $inactive)])
            ->withSum(['bookings as earned' => fn ($q) => $q->where('status', 'completed')], 'booking_services.price')
            ->orderByDesc('times_booked')
            ->limit(5)
            ->get();

        // Upcoming bookings (next 7 days), exclude archived
        $upcomingBookings = Booking::with(['customer.user', 'vehicle'])
            ->where('is_archived', false)
            ->whereBetween('booking_date', [now()->addDay()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->take(5)
            ->get();

        return view('livewire.business-owner.dashboard', [
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'recentBookings' => $recentBookings,
            'topServices' => $topServices,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
