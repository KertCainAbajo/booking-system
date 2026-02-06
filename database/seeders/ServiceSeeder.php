<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autoElectrical = ServiceCategory::where('name', 'Auto Electrical')->first();
        $preventiveMaintenance = ServiceCategory::where('name', 'Preventive Maintenance')->first();
        $autoAircon = ServiceCategory::where('name', 'Auto Aircon Services')->first();
        $underChassis = ServiceCategory::where('name', 'Under Chassis Services')->first();

        $services = [
            // Auto Electrical
            ['service_category_id' => $autoElectrical->id, 'name' => 'Wiring horn', 'description' => 'Horn wiring installation', 'base_price' => 500, 'estimated_duration_minutes' => 30],
            ['service_category_id' => $autoElectrical->id, 'name' => 'Signal light installation', 'description' => 'Install signal lights', 'base_price' => 800, 'estimated_duration_minutes' => 45],
            ['service_category_id' => $autoElectrical->id, 'name' => 'Fog lamp installation', 'description' => 'Install fog lamps', 'base_price' => 1200, 'estimated_duration_minutes' => 60],
            ['service_category_id' => $autoElectrical->id, 'name' => 'Headlight installation', 'description' => 'Install headlights', 'base_price' => 1500, 'estimated_duration_minutes' => 60],
            
            // Preventive Maintenance
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Oil filter replacement', 'description' => 'Replace oil filter', 'base_price' => 300, 'estimated_duration_minutes' => 15],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Fuel filter replacement', 'description' => 'Replace fuel filter', 'base_price' => 400, 'estimated_duration_minutes' => 20],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Air filter replacement', 'description' => 'Replace air filter', 'base_price' => 350, 'estimated_duration_minutes' => 15],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Engine oil change', 'description' => 'Change engine oil', 'base_price' => 800, 'estimated_duration_minutes' => 30],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Brake cleaning', 'description' => 'Clean brake system', 'base_price' => 600, 'estimated_duration_minutes' => 45],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Brake pad replacement (front)', 'description' => 'Replace front brake pads', 'base_price' => 1500, 'estimated_duration_minutes' => 60],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Brake pad replacement (rear)', 'description' => 'Replace rear brake pads', 'base_price' => 1400, 'estimated_duration_minutes' => 60],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Brake shoe replacement', 'description' => 'Replace brake shoes', 'base_price' => 1600, 'estimated_duration_minutes' => 75],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Brake fluid replacement', 'description' => 'Replace brake fluid', 'base_price' => 500, 'estimated_duration_minutes' => 30],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Gear oil replacement', 'description' => 'Replace gear oil', 'base_price' => 700, 'estimated_duration_minutes' => 30],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Transmission oil replacement', 'description' => 'Replace transmission oil', 'base_price' => 900, 'estimated_duration_minutes' => 45],
            ['service_category_id' => $preventiveMaintenance->id, 'name' => 'Radiator coolant replacement', 'description' => 'Replace radiator coolant', 'base_price' => 600, 'estimated_duration_minutes' => 30],
            
            // Auto Aircon Services
            ['service_category_id' => $autoAircon->id, 'name' => 'Cabin filter replacement', 'description' => 'Replace cabin air filter', 'base_price' => 400, 'estimated_duration_minutes' => 20],
            ['service_category_id' => $autoAircon->id, 'name' => 'Aircon system cleaning', 'description' => 'Clean aircon system', 'base_price' => 1200, 'estimated_duration_minutes' => 60],
            ['service_category_id' => $autoAircon->id, 'name' => 'Condenser replacement', 'description' => 'Replace aircon condenser', 'base_price' => 4500, 'estimated_duration_minutes' => 180],
            ['service_category_id' => $autoAircon->id, 'name' => 'Evaporator replacement', 'description' => 'Replace evaporator', 'base_price' => 5000, 'estimated_duration_minutes' => 240],
            ['service_category_id' => $autoAircon->id, 'name' => 'Compressor replacement', 'description' => 'Replace compressor', 'base_price' => 8000, 'estimated_duration_minutes' => 180],
            ['service_category_id' => $autoAircon->id, 'name' => 'Expansion valve replacement', 'description' => 'Replace expansion valve', 'base_price' => 2000, 'estimated_duration_minutes' => 90],
            ['service_category_id' => $autoAircon->id, 'name' => 'Alternator pulley assembly replacement', 'description' => 'Replace alternator pulley', 'base_price' => 3000, 'estimated_duration_minutes' => 120],
            
            // Under Chassis Services
            ['service_category_id' => $underChassis->id, 'name' => 'Shock absorber replacement', 'description' => 'Replace shock absorbers', 'base_price' => 2500, 'estimated_duration_minutes' => 90],
            ['service_category_id' => $underChassis->id, 'name' => 'Steering rack end replacement', 'description' => 'Replace steering rack end', 'base_price' => 2000, 'estimated_duration_minutes' => 120],
            ['service_category_id' => $underChassis->id, 'name' => 'Ball joint replacement', 'description' => 'Replace ball joints', 'base_price' => 1800, 'estimated_duration_minutes' => 90],
            ['service_category_id' => $underChassis->id, 'name' => 'Tie rod end replacement', 'description' => 'Replace tie rod ends', 'base_price' => 1500, 'estimated_duration_minutes' => 75],
            ['service_category_id' => $underChassis->id, 'name' => 'Turbo cleaning', 'description' => 'Clean turbocharger', 'base_price' => 3500, 'estimated_duration_minutes' => 150],
            ['service_category_id' => $underChassis->id, 'name' => 'EGR cleaning', 'description' => 'Clean EGR valve', 'base_price' => 1500, 'estimated_duration_minutes' => 90],
            ['service_category_id' => $underChassis->id, 'name' => 'Intake cleaning', 'description' => 'Clean intake manifold', 'base_price' => 1800, 'estimated_duration_minutes' => 120],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['is_active' => true]));
        }
    }
}
