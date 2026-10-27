<?php

namespace App\Models;

use App\Support\BookingNotifier;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $booking_reference
 * @property int $customer_id
 * @property int $vehicle_id
 * @property \Illuminate\Support\Carbon $booking_date
 * @property \Illuminate\Support\Carbon $booking_time
 * @property string $status
 * @property float $total_amount
 * @property string|null $notes
 * @property int|null $assigned_staff_id
 * @property string|null $mechanic_name
 * @property string|null $mechanic_phone
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Booking extends Model
{
    protected $fillable = [
        'booking_reference',
        'customer_id',
        'vehicle_id',
        'booking_date',
        'booking_time',
        'status',
        'total_amount',
        'notes',
        'assigned_staff_id',
        'mechanic_name',
        'mechanic_phone',
        'is_archived',
        'archived_at',
        'marked_as_late',
        'marked_late_at',
        'late_reason',
        'estimated_arrival_time'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i:s',
        'total_amount' => 'decimal:2',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'marked_as_late' => 'boolean',
        'marked_late_at' => 'datetime',
        'estimated_arrival_time' => 'datetime:H:i:s',
    ];

    /**
     * Generate a unique booking reference
     */
    public static function generateBookingReference(): string
    {
        // Random, not time-based: the reference alone lets a guest view and cancel a
        // booking, so it must not be guessable. No 0/O/1/I to keep it easy to read out.
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $reference = 'BK';
            for ($i = 0; $i < 10; $i++) {
                $reference .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Boot method to auto-generate booking reference
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateBookingReference();
            }
        });

        // Tell the customer and the owner about each step (email, bell and phone/desktop push)
        static::created(fn (Booking $booking) => BookingNotifier::created($booking));

        static::updated(function (Booking $booking) {
            if ($booking->wasChanged('status')) {
                BookingNotifier::statusChanged($booking, $booking->status);
            }
            if ($booking->wasChanged('mechanic_name') && filled($booking->mechanic_name)) {
                BookingNotifier::mechanicAssigned($booking);
            }
            if ($booking->wasChanged('marked_as_late') && $booking->marked_as_late) {
                BookingNotifier::markedLate($booking);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(BookingStatusLog::class);
    }

    /**
     * Check if the booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    /**
     * Check if the booking reference should be displayed
     * Reference is hidden once booking is completed
     */
    public function canShowReference(): bool
    {
        return $this->status !== 'completed';
    }

    /**
     * Cancel the booking with a reason
     */
    public function cancel(?int $userId = null, ?string $reason = null): void
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('This booking cannot be cancelled.');
        }

        $oldStatus = $this->status;
        $this->status = 'cancelled';
        $this->save();

        $this->statusLogs()->create([
            'old_status' => $oldStatus,
            'new_status' => 'cancelled',
            'changed_by' => $userId,
            'notes' => $reason ?? 'Booking cancelled'
        ]);
    }

    /**
     * Update booking status with logging
     */
    public function updateStatus(string $newStatus, ?int $userId = null, ?string $notes = null): void
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        $this->statusLogs()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $userId,
            'notes' => $notes
        ]);
    }
}
