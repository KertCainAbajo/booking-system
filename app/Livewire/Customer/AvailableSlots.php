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
    public $maxBookingsPerSlot = 1; // Maximum concurrent bookings per time slot

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function selectDate($date)
    {
        // Prevent selecting past dates
        $selectedCarbon = Carbon::parse($date);
        if ($selectedCarbon->isBefore(now()->startOfDay())) {
            $this->selectedDate = now()->format('Y-m-d');
        } else {
            $this->selectedDate = $date;
        }
    }

    public function isDateFullyBooked($date)
    {
        $dateCarbon = Carbon::parse($date);
        $availableCount = 0;
        
        foreach ($this->timeSlots as $time) {
            $slotDateTime = Carbon::parse($date . ' ' . $time);
            
            // Skip past time slots
            if ($slotDateTime->isPast()) {
                continue;
            }
            
            // Count existing bookings for this slot
            $bookingCount = Booking::whereDate('booking_date', $date)
                ->whereTime('booking_time', $time)
                ->whereIn('status', ['pending', 'approved', 'in_progress'])
                ->count();
            
            $availableSpots = $this->maxBookingsPerSlot - $bookingCount;
            
            if ($availableSpots > 0) {
                $availableCount++;
            }
        }
        
        return $availableCount === 0;
    }

    public function getAvailableDates()
    {
        $dates = [];
        $startDate = now()->startOfDay();
        
        for ($i = 0; $i < $this->daysToShow; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            // Skip Sundays (you can modify this based on your business days)
            if ($date->dayOfWeek === 0) { // 0 = Sunday
                continue;
            }
            
            $dateFormatted = $date->format('Y-m-d');
            $isFullyBooked = $this->isDateFullyBooked($dateFormatted);
            
            $dates[] = [
                'date' => $dateFormatted,
                'day' => $date->format('l'),
                'dayNum' => $date->format('d'),
                'month' => $date->format('M'),
                'isPast' => $date->isPast(),
                'isToday' => $date->isToday(),
                'isFullyBooked' => $isFullyBooked,
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
        // Auto-reset to today if selected date is in the past
        if ($this->selectedDate && Carbon::parse($this->selectedDate)->isBefore(now()->startOfDay())) {
            $this->selectedDate = now()->format('Y-m-d');
        }
        
        return view('livewire.customer.available-slots', [
            'availableDates' => $this->getAvailableDates(),
            'selectedDateSlots' => $this->getSelectedDateSlots(),
        ]);
    }
}
