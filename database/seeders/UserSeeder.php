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
        $adminRole = Role::where('name', 'super_admin')->first();
        $ownerRole = Role::where('name', 'business_owner')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Keep convenient development credentials, but require explicit passwords in production.
        $isProduction = app()->environment('production');
        $defaultPassword = $isProduction ? '' : 'password';
        $adminPassword = env('SEED_ADMIN_PASSWORD', $defaultPassword);
        $ownerPassword = env('SEED_OWNER_PASSWORD', $defaultPassword);
        $customerPassword = env('SEED_CUSTOMER_PASSWORD', $isProduction ? '' : 'customer123');

        if ($isProduction && collect([
            $adminPassword,
            $ownerPassword,
            $customerPassword,
        ])->contains(fn ($password) => blank($password))) {
            throw new \RuntimeException('Production seed passwords must be set in the environment before seeding users.');
        }

        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'azdead28@gmail.com',
            'phone' => '09123456789',
            'password' => Hash::make($adminPassword),
            'role_id' => $adminRole->id,
        ]);

        // Business Owner
        User::create([
            'name' => 'Business Owner',
            'email' => 'slayerdead917@gmail.com',
            'phone' => '09123456788',
            'password' => Hash::make($ownerPassword),
            'role_id' => $ownerRole->id,
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
