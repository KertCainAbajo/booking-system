<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'quantity_in_stock',
        'reorder_level',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_inventory')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }
}
