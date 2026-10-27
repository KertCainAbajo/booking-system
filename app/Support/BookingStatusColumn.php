<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Changes the allowed values of bookings.status in a way that works on every
 * database: a direct ENUM change on MySQL, a CHECK constraint swap on PostgreSQL,
 * and Laravel's column change elsewhere (e.g. the in-memory SQLite test database).
 */
class BookingStatusColumn
{
    public static function set(array $statuses): void
    {
        if (DB::getDriverName() === 'mysql') {
            $list = implode(', ', array_map(fn ($s) => DB::getPdo()->quote($s), $statuses));
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM($list) NOT NULL DEFAULT 'pending'");

            return;
        }

        // PostgreSQL (Supabase): Laravel stores an "enum" as text plus a CHECK constraint, so swap the constraint
        if (DB::getDriverName() === 'pgsql') {
            $list = implode(', ', array_map(fn ($s) => DB::getPdo()->quote($s), $statuses));
            DB::statement('ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check');
            DB::statement("ALTER TABLE bookings ALTER COLUMN status SET DEFAULT 'pending'");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status IN ($list))");

            return;
        }

        Schema::table('bookings', function (Blueprint $table) use ($statuses) {
            $table->enum('status', $statuses)->default('pending')->change();
        });
    }
}
