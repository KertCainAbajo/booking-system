<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use Carbon\Carbon;

#[Layout('layouts.staff')]
class BookingCalendar extends Component
{
    public $statusFilter = 'all';
    public $viewMode = 'calendar'; // calendar, list
    public $currentMonth;
    public $currentYear;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function today()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function getCalendarData()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startDayOfWeek = $firstDay->dayOfWeek; // 0 = Sunday

        // Get all bookings for this month
        $bookingsQuery = Booking::with(['customer.user', 'services'])
            ->whereYear('booking_date', $this->currentYear)
            ->whereMonth('booking_date', $this->currentMonth);

        if ($this->statusFilter !== 'all') {
            $bookingsQuery->where('status', $this->statusFilter);
        }

        $bookings = $bookingsQuery->get()->groupBy(function($booking) {
            return Carbon::parse($booking->booking_date)->format('Y-m-d');
        });

        $calendar = [];
        $day = 1;

        // Create 6 weeks to accommodate all possible month layouts
        for ($week = 0; $week < 6; $week++) {
            $calendar[$week] = [];
            for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) {
                if (($week === 0 && $dayOfWeek < $startDayOfWeek) || $day > $daysInMonth) {
                    $calendar[$week][$dayOfWeek] = null;
                } else {
                    $date = Carbon::create($this->currentYear, $this->currentMonth, $day);
                    $dateKey = $date->format('Y-m-d');
                    $calendar[$week][$dayOfWeek] = [
                        'day' => $day,
                        'date' => $date,
                        'bookings' => $bookings->get($dateKey, collect()),
                        'isToday' => $date->isToday(),
                        'isPast' => $date->isPast() && !$date->isToday(),
                    ];
                    $day++;
                }
            }
            // Stop if we've filled all days
            if ($day > $daysInMonth) {
                break;
            }
        }

        return $calendar;
    }

    public function render()
    {
        // Get monthly stats
        $monthStart = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfDay();
        $monthEnd = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth()->endOfDay();

        $statsQuery = Booking::whereBetween('booking_date', [$monthStart, $monthEnd]);
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
        ];

        $calendar = $this->getCalendarData();
        $monthName = Carbon::create($this->currentYear, $this->currentMonth, 1)->format('F Y');

        return view('livewire.staff.booking-calendar', [
            'calendar' => $calendar,
            'monthName' => $monthName,
            'stats' => $stats,
        ]);
    }
}
