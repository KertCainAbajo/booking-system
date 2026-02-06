<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Auto Electrical'],
            ['name' => 'Preventive Maintenance'],
            ['name' => 'Auto Aircon Services'],
            ['name' => 'Under Chassis Services'],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
