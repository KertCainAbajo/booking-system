<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('marked_as_late')->default(false)->after('notes');
            $table->timestamp('marked_late_at')->nullable()->after('marked_as_late');
            $table->text('late_reason')->nullable()->after('marked_late_at');
            $table->time('estimated_arrival_time')->nullable()->after('late_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['marked_as_late', 'marked_late_at', 'late_reason', 'estimated_arrival_time']);
        });
    }
};
