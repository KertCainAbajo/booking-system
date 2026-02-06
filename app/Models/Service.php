<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_category_id',
        'name',
        'description',
        'base_price',
        'estimated_duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function inventoryItems()
    {
        return $this->belongsToMany(InventoryItem::class, 'service_inventory')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }
}
