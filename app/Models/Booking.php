<?php

namespace App\Models;

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
        'is_archived',
        'archived_at',
        'marked_as_late',
        'marked_late_at',
        'late_reason',
        'estimated_arrival_time'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'marked_as_late' => 'boolean',
        'marked_late_at' => 'datetime',
        'estimated_arrival_time' => 'datetime:H:i',
    ];

    /**
     * Generate a unique booking reference
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BK' . strtoupper(substr(uniqid(), -8));
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
