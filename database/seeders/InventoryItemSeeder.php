<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Oil Filter', 'sku' => 'OF-001', 'quantity_in_stock' => 50, 'reorder_level' => 10, 'unit_price' => 150],
            ['name' => 'Fuel Filter', 'sku' => 'FF-001', 'quantity_in_stock' => 40, 'reorder_level' => 10, 'unit_price' => 200],
            ['name' => 'Air Filter', 'sku' => 'AF-001', 'quantity_in_stock' => 45, 'reorder_level' => 10, 'unit_price' => 180],
            ['name' => 'Brake Pad Front', 'sku' => 'BPF-001', 'quantity_in_stock' => 30, 'reorder_level' => 8, 'unit_price' => 800],
            ['name' => 'Brake Pad Rear', 'sku' => 'BPR-001', 'quantity_in_stock' => 30, 'reorder_level' => 8, 'unit_price' => 750],
            ['name' => 'Brake Shoe', 'sku' => 'BS-001', 'quantity_in_stock' => 25, 'reorder_level' => 5, 'unit_price' => 900],
            ['name' => 'Engine Oil (4L)', 'sku' => 'EO-001', 'quantity_in_stock' => 60, 'reorder_level' => 15, 'unit_price' => 450],
            ['name' => 'Brake Fluid', 'sku' => 'BF-001', 'quantity_in_stock' => 35, 'reorder_level' => 10, 'unit_price' => 250],
            ['name' => 'Transmission Oil', 'sku' => 'TO-001', 'quantity_in_stock' => 40, 'reorder_level' => 10, 'unit_price' => 500],
            ['name' => 'Radiator Coolant', 'sku' => 'RC-001', 'quantity_in_stock' => 50, 'reorder_level' => 12, 'unit_price' => 300],
            ['name' => 'Cabin Filter', 'sku' => 'CF-001', 'quantity_in_stock' => 35, 'reorder_level' => 10, 'unit_price' => 220],
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}
