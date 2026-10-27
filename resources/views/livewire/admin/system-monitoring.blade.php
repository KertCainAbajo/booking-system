@php
    $checks = [
        [
            'label' => 'Database',
            'ok' => $database['ok'],
            'value' => $database['ok'] ? 'Connected' : 'Unreachable',
            'detail' => $database['ok']
                ? strtoupper($database['driver']) . ' · ' . $database['name'] . ' · ' . $database['ms'] . ' ms' . ($database['size_mb'] !== null ? ' · ' . $database['size_mb'] . ' MB' : '')
                : \Illuminate\Support\Str::limit($database['error'] ?? 'Unknown error', 120),
        ],
        [
            'label' => 'Background jobs',
            'ok' => ($queue['failed'] ?? 0) === 0,
            'value' => $queue['failed'] === null ? 'Not tracked' : (($queue['failed'] ?? 0) . ' failed'),
            'detail' => 'Queue: ' . $queue['driver'] . ($queue['pending'] !== null ? ' · ' . $queue['pending'] . ' waiting' : ''),
        ],
        [
            'label' => 'Nightly archive',
            'ok' => $overdueToArchive === 0,
            'value' => $overdueToArchive === 0 ? 'Up to date' : $overdueToArchive . ' waiting',
            'detail' => $lastArchive ? 'Last run ' . $lastArchive->diffForHumans() : 'Has not archived anything yet',
        ],
        [
            'label' => 'Disk space',
            'ok' => $system['disk_used_pct'] === null || $system['disk_used_pct'] < 90,
            'value' => $system['disk_free_gb'] !== null ? $system['disk_free_gb'] . ' GB free' : 'Unknown',
            'detail' => $system['disk_used_pct'] !== null ? $system['disk_used_pct'] . '% used · log file ' . $system['log_size_mb'] . ' MB' : 'Log file ' . $system['log_size_mb'] . ' MB',
        ],
    ];
    $failing = collect($checks)->where('ok', false)->count();
    $debugInProduction = $system['debug'] && $system['app_env'] === 'production';
@endphp

<div class="space-y-6" wire:poll.60s>
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-sky-300">Monitoring</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">System health</h1>
            <p class="mt-1 text-gray-400">Checked {{ $checkedAt->format('g:i:s A') }} · refreshes every minute</p>
        </div>
        <button wire:click="$refresh" class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-white/[0.05] ring-1 ring-white/10 text-sm font-semibold text-gray-200 hover:bg-white/10 transition">
            <svg class="w-4 h-4" wire:loading.class="animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Check now
        </button>
    </div>

    <!-- Overall status -->
    <div @class([
        'flex items-center gap-4 rounded-3xl p-5 ring-1',
        'bg-emerald-500/10 ring-emerald-500/30' => $failing === 0 && ! $debugInProduction,
        'bg-amber-500/10 ring-amber-500/30' => $failing > 0 || $debugInProduction,
    ])>
        <span @class(['w-12 h-12 rounded-2xl flex items-center justify-center', 'bg-emerald-400 text-gray-950' => $failing === 0 && ! $debugInProduction, 'bg-amber-400 text-gray-950' => $failing > 0 || $debugInProduction])>
            @if ($failing === 0 && ! $debugInProduction)
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @else
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            @endif
        </span>
        <div>
            <p class="text-lg font-bold text-white">
                {{ $failing === 0 && ! $debugInProduction ? 'All systems normal' : ($failing + ($debugInProduction ? 1 : 0)) . ' ' . \Illuminate\Support\Str::plural('thing', $failing + ($debugInProduction ? 1 : 0)) . ' need attention' }}
            </p>
            <p class="text-sm text-gray-300">
                @if ($debugInProduction)
                    Debug mode is ON in production. Visitors can see error details. Set APP_DEBUG=false.
                @elseif ($failing > 0)
                    See the checks marked in amber below.
                @else
                    Database, background jobs, archiving and disk space all look fine.
                @endif
            </p>
        </div>
    </div>

    <!-- Checks -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">
        @foreach ($checks as $check)
            <div class="cp-card p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-400">{{ $check['label'] }}</p>
                    <span @class(['cp-badge', 'bg-emerald-400/10 text-emerald-300 ring-emerald-400/30' => $check['ok'], 'bg-amber-400/10 text-amber-300 ring-amber-400/30' => ! $check['ok']])>
                        {{ $check['ok'] ? 'OK' : 'Check' }}
                    </span>
                </div>
                <p class="mt-3 text-xl font-bold text-white">{{ $check['value'] }}</p>
                <p class="mt-1 text-xs text-gray-500 break-words">{{ $check['detail'] }}</p>
            </div>
        @endforeach
    </section>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem] items-start">
        <div class="space-y-6 min-w-0">
            <!-- Errors -->
            <section class="cp-card p-5 sm:p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-lg font-bold text-white">Latest errors</h2>
                    <span class="text-xs text-gray-500">from storage/logs/laravel.log</span>
                </div>
                <ul class="mt-4 space-y-2">
                    @forelse ($errors as $error)
                        <li class="rounded-xl bg-black/30 ring-1 ring-white/[0.06] p-3">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="cp-badge !py-0 bg-rose-400/10 text-rose-300 ring-rose-400/30">{{ $error['level'] }}</span>
                                <span class="text-gray-500">{{ \Carbon\Carbon::parse($error['time'])->format('M j, g:i A') }} · {{ \Carbon\Carbon::parse($error['time'])->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1.5 font-mono text-xs text-gray-300 break-words">{{ $error['message'] }}</p>
                        </li>
                    @empty
                        <li class="flex items-center gap-2 py-6 justify-center text-sm text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            No errors in the recent log
                        </li>
                    @endforelse
                </ul>
            </section>

            <!-- Activity -->
            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-lg font-bold text-white">Recent booking activity</h2>
                <ul class="mt-4 divide-y divide-white/[0.05]">
                    @forelse ($recentActivity as $log)
                        <li class="flex items-start justify-between gap-3 py-3 text-sm">
                            <span class="text-gray-200">
                                <span class="font-semibold text-white">{{ $log->user?->name ?? 'System' }}</span>
                                set
                                @if ($log->booking)
                                    <a href="{{ route('admin.booking.detail', $log->booking_id) }}" wire:navigate class="text-sky-300 hover:text-sky-200">#{{ str_pad($log->booking_id, 4, '0', STR_PAD_LEFT) }}</a>
                                @else
                                    a deleted booking
                                @endif
                                from {{ ucwords(str_replace('_', ' ', (string) $log->old_status)) }} to <span class="font-semibold text-white">{{ ucwords(str_replace('_', ' ', $log->new_status)) }}</span>
                            </span>
                            <span class="text-xs text-gray-500 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-gray-500">No status changes yet.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        <!-- Side column -->
        <div class="space-y-6">
            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-base font-bold text-white">Application</h2>
                <dl class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Environment</dt><dd class="text-white">{{ ucfirst($system['app_env']) }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Debug mode</dt><dd class="{{ $system['debug'] ? 'text-amber-300' : 'text-emerald-300' }}">{{ $system['debug'] ? 'On' : 'Off' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Laravel</dt><dd class="text-white">{{ $system['laravel'] }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">PHP</dt><dd class="text-white">{{ $system['php'] }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Timezone</dt><dd class="text-white">{{ $system['timezone'] }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Sessions</dt><dd class="text-white">{{ $system['session_driver'] }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Cache</dt><dd class="text-white">{{ $system['cache_driver'] }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-gray-400">App URL</dt><dd class="text-white truncate">{{ $system['url'] }}</dd></div>
                </dl>
            </section>

            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-base font-bold text-white">Signed in now</h2>
                @if ($activeUsers !== null)
                    <p class="mt-3 text-3xl font-bold text-white">{{ $activeUsers }}</p>
                    <p class="text-sm text-gray-400">{{ \Illuminate\Support\Str::plural('user', $activeUsers) }} active in the last 15 minutes</p>
                @else
                    <p class="mt-2 text-sm text-gray-400">Not available: sessions are stored as <span class="font-mono text-gray-300">{{ $system['session_driver'] }}</span>. Set <span class="font-mono text-gray-300">SESSION_DRIVER=database</span> to see this.</p>
                @endif
            </section>

            @if ($tables)
                <section class="cp-card p-5 sm:p-6">
                    <h2 class="text-base font-bold text-white">Records</h2>
                    <dl class="mt-4 space-y-2.5 text-sm">
                        @foreach ($tables as $label => $count)
                            <div class="flex justify-between gap-3"><dt class="text-gray-400">{{ $label }}</dt><dd class="font-semibold text-white tabular-nums">{{ number_format($count) }}</dd></div>
                        @endforeach
                    </dl>
                </section>
            @endif
        </div>
    </div>
</div>
