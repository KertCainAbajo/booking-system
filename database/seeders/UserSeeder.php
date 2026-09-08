<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'it_admin')->first();
        $ownerRole = Role::where('name', 'business_owner')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Keep convenient development credentials, but require explicit passwords in production.
        $isProduction = app()->environment('production');
        $defaultPassword = $isProduction ? '' : 'password';
        $adminPassword = env('SEED_ADMIN_PASSWORD', $defaultPassword);
        $ownerPassword = env('SEED_OWNER_PASSWORD', $defaultPassword);
        $staffPassword = env('SEED_STAFF_PASSWORD', $defaultPassword);
        $customerPassword = env('SEED_CUSTOMER_PASSWORD', $isProduction ? '' : 'customer123');

        if ($isProduction && collect([
            $adminPassword,
            $ownerPassword,
            $staffPassword,
            $customerPassword,
        ])->contains(fn ($password) => blank($password))) {
            throw new \RuntimeException('Production seed passwords must be set in the environment before seeding users.');
        }

        // IT Admin
        User::create([
            'name' => 'IT Administrator',
            'email' => 'admin@autoservice.com',
            'phone' => '09123456789',
            'password' => Hash::make($adminPassword),
            'role_id' => $adminRole->id,
        ]);

        // Business Owner
        User::create([
            'name' => 'Business Owner',
            'email' => 'owner@autoservice.com',
            'phone' => '09123456788',
            'password' => Hash::make($ownerPassword),
            'role_id' => $ownerRole->id,
        ]);

        // Staff
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@autoservice.com',
            'phone' => '09123456787',
            'password' => Hash::make($staffPassword),
            'role_id' => $staffRole->id,
        ]);

        // Customer
        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@autoservice.com',
            'phone' => '09123456786',
            'password' => Hash::make($customerPassword),
            'role_id' => $customerRole->id,
        ]);

        // Create customer profile
        Customer::create([
            'user_id' => $customer->id,
            'address' => '123 Main Street, City, Philippines',
        ]);
    }
}
