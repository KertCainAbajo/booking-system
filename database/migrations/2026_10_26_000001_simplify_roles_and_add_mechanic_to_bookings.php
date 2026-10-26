<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The system now has three roles: customer, business_owner and super_admin.
 * - it_admin is renamed to super_admin.
 * - staff is removed; the owner now handles day-to-day bookings, so former
 *   staff accounts are moved to business_owner.
 * - Mechanics no longer need accounts: bookings store a typed mechanic name/phone.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('mechanic_name')->nullable()->after('assigned_staff_id');
            $table->string('mechanic_phone', 30)->nullable()->after('mechanic_name');
        });

        // Keep existing assignments by copying the assigned user's name and phone
        DB::table('bookings')
            ->join('users', 'users.id', '=', 'bookings.assigned_staff_id')
            ->whereNotNull('bookings.assigned_staff_id')
            ->update([
                'bookings.mechanic_name' => DB::raw('users.name'),
                'bookings.mechanic_phone' => DB::raw('users.phone'),
            ]);

        DB::table('roles')->where('name', 'it_admin')->update(['name' => 'super_admin', 'updated_at' => now()]);

        $staffRoleId = DB::table('roles')->where('name', 'staff')->value('id');
        if ($staffRoleId) {
            $ownerRoleId = DB::table('roles')->where('name', 'business_owner')->value('id');
            if (! $ownerRoleId) {
                $ownerRoleId = DB::table('roles')->insertGetId(['name' => 'business_owner', 'created_at' => now(), 'updated_at' => now()]);
            }

            DB::table('users')->where('role_id', $staffRoleId)->update(['role_id' => $ownerRoleId]);
            DB::table('roles')->where('id', $staffRoleId)->delete();
        }
    }

    public function down(): void
    {
        // Former staff accounts cannot be told apart from owners, so they stay owners.
        if (! DB::table('roles')->where('name', 'staff')->exists()) {
            DB::table('roles')->insert(['name' => 'staff', 'created_at' => now(), 'updated_at' => now()]);
        }
        DB::table('roles')->where('name', 'super_admin')->update(['name' => 'it_admin', 'updated_at' => now()]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['mechanic_name', 'mechanic_phone']);
        });
    }
};
