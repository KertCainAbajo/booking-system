<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

#[Layout('layouts.admin')]
class SystemMonitoring extends Component
{
    /** Database reachability, size and response time. */
    protected function databaseHealth(): array
    {
        try {
            $started = microtime(true);
            DB::select('select 1');
            $ms = round((microtime(true) - $started) * 1000, 1);

            $sizeMb = null;
            if (DB::getDriverName() === 'mysql') {
                $sizeMb = round((float) DB::table('information_schema.tables')
                    ->where('table_schema', DB::getDatabaseName())
                    ->sum(DB::raw('data_length + index_length')) / 1048576, 2);
            } elseif (DB::getDriverName() === 'pgsql') {
                $sizeMb = round((float) DB::selectOne('select pg_database_size(current_database()) as size')->size / 1048576, 2);
            }

            return ['ok' => true, 'ms' => $ms, 'size_mb' => $sizeMb, 'name' => DB::getDatabaseName(), 'driver' => DB::getDriverName()];
        } catch (Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage(), 'driver' => DB::getDriverName()];
        }
    }

    /** Latest ERROR entries from the application log (newest first). */
    protected function recentErrors(int $limit = 6): array
    {
        $path = storage_path('logs/laravel.log');
        if (! is_file($path)) {
            return [];
        }

        // Only read the tail of the file so a large log doesn't slow the page down
        $handle = fopen($path, 'r');
        $size = filesize($path);
        fseek($handle, max(0, $size - 256 * 1024));
        $tail = stream_get_contents($handle);
        fclose($handle);

        preg_match_all('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(ERROR|CRITICAL|ALERT|EMERGENCY): (.+)$/m', $tail, $matches, PREG_SET_ORDER);

        return collect($matches)
            ->reverse()
            ->take($limit)
            ->map(fn ($m) => [
                'time' => $m[1],
                'level' => $m[2],
                // Drop the JSON context and trim long messages
                'message' => \Illuminate\Support\Str::limit(trim(preg_replace('/\s\{".*$/', '', $m[3])), 220),
            ])
            ->values()
            ->all();
    }

    public function render()
    {
        $database = $this->databaseHealth();

        $sessionDriver = config('session.driver');
        $activeUsers = null;
        if ($sessionDriver === 'database' && $database['ok'] && Schema::hasTable(config('session.table', 'sessions'))) {
            $activeUsers = DB::table(config('session.table', 'sessions'))
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
                ->distinct('user_id')
                ->count('user_id');
        }

        $queue = [
            'driver' => config('queue.default'),
            'pending' => $database['ok'] && Schema::hasTable('jobs') ? DB::table('jobs')->count() : null,
            'failed' => $database['ok'] && Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : null,
        ];

        $diskTotal = @disk_total_space(storage_path());
        $diskFree = @disk_free_space(storage_path());
        $logPath = storage_path('logs/laravel.log');

        // The nightly bookings:archive job moves finished bookings to History; a backlog means the scheduler isn't running
        $lastArchive = $database['ok'] ? Booking::max('archived_at') : null;
        $overdueToArchive = $database['ok'] ? Booking::where('is_archived', false)
            ->whereIn('status', ['completed', 'cancelled', 'no_show']) // same statuses the job archives
            ->whereDate('booking_date', '<', today()->subDay())        // one day of slack before calling it a backlog
            ->count() : 0;

        $system = [
            'app_env' => app()->environment(),
            'debug' => (bool) config('app.debug'),
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'timezone' => config('app.timezone'),
            'url' => config('app.url'),
            'session_driver' => $sessionDriver,
            'cache_driver' => config('cache.default'),
            'disk_free_gb' => $diskFree ? round($diskFree / 1073741824, 1) : null,
            'disk_used_pct' => ($diskTotal && $diskFree) ? round((1 - $diskFree / $diskTotal) * 100) : null,
            'log_size_mb' => is_file($logPath) ? round(filesize($logPath) / 1048576, 2) : 0,
        ];

        $tables = $database['ok'] ? [
            'Users' => User::count(),
            'Bookings' => Booking::count(),
            'Archived bookings' => Booking::where('is_archived', true)->count(),
            'Status changes' => BookingStatusLog::count(),
        ] : [];

        $recentActivity = $database['ok']
            ? BookingStatusLog::with(['user', 'booking.customer.user'])->latest()->limit(10)->get()
            : collect();

        return view('livewire.admin.system-monitoring', [
            'database' => $database,
            'activeUsers' => $activeUsers,
            'queue' => $queue,
            'system' => $system,
            'lastArchive' => $lastArchive ? \Carbon\Carbon::parse($lastArchive) : null,
            'overdueToArchive' => $overdueToArchive,
            'tables' => $tables,
            'errors' => $this->recentErrors(),
            'recentActivity' => $recentActivity,
            'checkedAt' => now(),
        ]);
    }
}
