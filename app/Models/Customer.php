<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_guest
 * @property string|null $address
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * 
 * @phpstan-property int $id
 * @psalm-property int $id
 */
class Customer extends Model
{
    protected $fillable = ['user_id', 'name', 'email', 'phone', 'is_guest', 'address'];

    protected $casts = [
        'is_guest' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is a guest customer
     */
    public function isGuest(): bool
    {
        return $this->is_guest || $this->user_id === null;
    }

    /**
     * Get display name for customer
     */
    public function getDisplayName(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->name ?? 'Guest Customer';
    }

    /**
     * Get contact email for customer
     */
    public function getContactEmail(): string
    {
        if ($this->user) {
            return $this->user->email;
        }
        return $this->email ?? '';
    }

    /**
     * Get contact phone for customer
     */
    public function getContactPhone(): string
    {
        if ($this->user) {
            return $this->user->phone ?? '';
        }
        return $this->phone ?? '';
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
