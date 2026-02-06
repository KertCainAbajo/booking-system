<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'it_admin'],
            ['name' => 'business_owner'],
            ['name' => 'staff'],
            ['name' => 'customer'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
