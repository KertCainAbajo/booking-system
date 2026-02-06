<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceInventory extends Model
{
    protected $fillable = [
        'service_id',
        'inventory_item_id',
        'quantity_required'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
