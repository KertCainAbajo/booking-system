<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Booking;
use Carbon\Carbon;

class AvailableSlots extends Component
{
    public $selectedDate;
    public $daysToShow = 7; // Show next 7 days
    public $timeSlots = [
        '08:00', '09:00', '10:00', '11:00', 
        '13:00', '14:00', '15:00', '16:00', '17:00'
    ];
    
    // Business hours configuration
    public $openHour = 8;
    public $closeHour = 18;
    public $lunchBreakStart = 12;
    public $lunchBreakEnd = 13;
    public $maxBookingsPerSlot = 3; // Maximum concurrent bookings per time slot

    public function mount()
    {
        $this->selectedDate = now()->addDay()->format('Y-m-d');
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function getAvailableDates()
    {
        $dates = [];
        $startDate = now()->startOfDay();
        
        for ($i = 1; $i <= $this->daysToShow; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            // Skip Sundays (you can modify this based on your business days)
            if ($date->dayOfWeek === Carbon::SUNDAY) {
                continue;
            }
            
            $dates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l'),
                'dayNum' => $date->format('d'),
                'month' => $date->format('M'),
                'isPast' => $date->isPast(),
                'isToday' => $date->isToday(),
            ];
        }
        
        return $dates;
    }

    public function getAvailableSlotsForDate($date)
    {
        $slots = [];
        $dateCarbon = Carbon::parse($date);
        
        foreach ($this->timeSlots as $time) {
            $slotDateTime = Carbon::parse($date . ' ' . $time);
            
            // Skip past time slots for today
            if ($slotDateTime->isPast()) {
                continue;
            }
            
            // Count existing bookings for this slot
            $bookingCount = Booking::whereDate('booking_date', $date)
                ->whereTime('booking_time', $time)
                ->whereIn('status', ['pending', 'approved', 'in_progress'])
                ->count();
            
            $availableSpots = $this->maxBookingsPerSlot - $bookingCount;
            
            $slots[] = [
                'time' => $time,
                'displayTime' => Carbon::parse($time)->format('h:i A'),
                'isAvailable' => $availableSpots > 0,
                'availableSpots' => $availableSpots,
                'isFull' => $availableSpots <= 0,
                'isLimited' => $availableSpots > 0 && $availableSpots <= 1,
            ];
        }
        
        return $slots;
    }

    public function getSelectedDateSlots()
    {
        if (!$this->selectedDate) {
            return [];
        }
        
        return $this->getAvailableSlotsForDate($this->selectedDate);
    }

    public function render()
    {
        return view('livewire.customer.available-slots', [
            'availableDates' => $this->getAvailableDates(),
            'selectedDateSlots' => $this->getSelectedDateSlots(),
        ]);
    }
}
