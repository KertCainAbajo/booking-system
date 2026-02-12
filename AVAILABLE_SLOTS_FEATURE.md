# Available Slots Feature - Customer Dashboard

## Overview
Added a new feature to the customer dashboard that displays available booking dates and time slots in real-time.

## Files Created

### 1. Livewire Component
**File:** `app/Livewire/Customer/AvailableSlots.php`
- Shows next 7 days of available booking dates
- Displays time slots from 8:00 AM to 5:00 PM
- Excludes Sundays (can be customized)
- Excludes lunch break (12:00 PM - 1:00 PM)
- Tracks booking capacity (max 3 concurrent bookings per slot)
- Real-time availability checking

### 2. Blade View
**File:** `resources/views/livewire/customer/available-slots.blade.php`
- Interactive date selector with visual feedback
- Time slot grid showing availability status
- Color-coded indicators:
  - **Green (Neon)**: Available slots (2-3 spots open)
  - **Yellow**: Limited availability (1 spot left)
  - **Red**: Fully booked
- Information section with booking rules
- Responsive design for mobile/tablet/desktop
- Matches existing garage theme styling

## Integration
Updated `resources/views/livewire/customer/dashboard.blade.php` to include the new component between Quick Actions and Service Records sections.

## Features

### Date Selection
- Shows next 7 days
- Excludes Sundays
- Visual highlighting for selected date
- Day name, date, and month display

### Time Slot Display
- Visual availability indicators
- Real-time booking count
- Shows exact number of spots available
- Prevents showing past time slots
- Clear "Fully Booked" status

### Business Rules (Configurable)
- **Operating Hours**: 8:00 AM - 6:00 PM
- **Lunch Break**: 12:00 PM - 1:00 PM
- **Closed Days**: Sundays
- **Max Concurrent Bookings**: 3 per time slot

### Status Tracking
The component checks bookings with statuses:
- Pending
- Approved
- In Progress

Completed and cancelled bookings don't count toward slot capacity.

## Customization Options

You can modify these properties in `AvailableSlots.php`:

```php
public $daysToShow = 7;              // Number of days to display
public $maxBookingsPerSlot = 3;      // Max bookings per slot
public $openHour = 8;                // Business start time
public $closeHour = 18;              // Business end time
public $lunchBreakStart = 12;        // Lunch break start
public $lunchBreakEnd = 13;          // Lunch break end
```

To change available time slots, modify:
```php
public $timeSlots = [
    '08:00', '09:00', '10:00', '11:00', 
    '13:00', '14:00', '15:00', '16:00', '17:00'
];
```

## User Experience

### Customer Benefits
1. **Quick Overview**: See availability at a glance
2. **Better Planning**: Choose optimal booking times
3. **Reduced Frustration**: Know which slots are available before starting booking process
4. **Time Saving**: Don't waste time selecting unavailable slots

### Visual Design
- Matches existing garage theme with carbon fiber patterns
- Neon green accent colors
- Service tag typography for consistency
- Smooth transitions and hover effects
- Mobile-responsive grid layout

## Next Steps (Optional Enhancements)

1. **Direct Booking**: Click time slot to start booking with pre-filled date/time
2. **Week/Month View**: Toggle between different calendar views
3. **Service Duration**: Show slots based on selected service duration
4. **Push Notifications**: Alert when preferred slots become available
5. **Historical Data**: Show most popular booking times
6. **Staff Assignment**: Show which staff members are available per slot

## Testing

To test the feature:
1. Log in as a customer
2. Navigate to customer dashboard
3. View the "Available Slots" section
4. Select different dates to see time slot availability
5. Click "BOOK NOW" to proceed with booking

The component automatically:
- Hides past time slots
- Updates availability based on existing bookings
- Shows real-time capacity information
