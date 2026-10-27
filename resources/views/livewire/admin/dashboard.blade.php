@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first();
    $trend = function (?float $change) {
        if ($change === null) return ['text' => 'No data last month', 'class' => 'text-gray-500'];
        if ($change == 0) return ['text' => 'Same as last month', 'class' => 'text-gray-500'];
        return ['text' => ($change > 0 ? '▲ ' : '▼ ') . abs($change) . '% vs last month', 'class' => $change > 0 ? 'text-emerald-300' : 'text-rose-300'];
    };
    $statusTotal = max(1, $bookingsByStatus->sum());
    $statusBars = [
        'pending' => ['Pending', 'bg-amber-400'],
        'approved' => ['Approved', 'bg-sky-400'],
        'completed' => ['Completed', 'bg-emerald-400'],
        'cancelled' => ['Cancelled', 'bg-rose-400'],
        'no_show' => ['No show', 'bg-orange-400'],
        'not_available' => ['Not available', 'bg-gray-400'],
    ];
    $roleRows = [
        'customer' => ['Customers', 'bg-emerald-400'],
        'business_owner' => ['Owners', 'bg-violet-400'],
        'super_admin' => ['Super Admins', 'bg-sky-400'],
    ];
    $roleLabel = fn ($n) => ['super_admin' => 'Super Admin', 'business_owner' => 'Owner', 'customer' => 'Customer'][$n] ?? $n;
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-sky-300">{{ now()->format('l, F j, Y') }}</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">{{ $greeting }}, {{ $firstName }}</h1>
            <p class="mt-1 text-gray-400">System overview for Dexter Auto Services.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.users') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-sky-400 text-gray-950 text-sm font-semibold hover:bg-sky-300 transition">Manage users</a>
            <a href="{{ route('admin.monitoring') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-white/[0.05] ring-1 ring-white/10 text-sm font-semibold text-gray-200 hover:bg-white/10 transition">System health</a>
        </div>
    </div>

    <!-- Attention -->
    @if ($stats['pending_bookings'] > 0 || $lowStock->isNotEmpty())
        <div class="flex flex-wrap gap-3">
            @if ($stats['pending_bookings'] > 0)
                <a href="{{ route('admin.bookings') }}" wire:navigate class="flex items-center gap-3 rounded-2xl bg-amber-400/10 ring-1 ring-amber-400/40 px-4 py-3 hover:ring-amber-400/70 transition">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span class="text-sm text-amber-100"><span class="font-bold">{{ $stats['pending_bookings'] }}</span> {{ \Illuminate\Support\Str::plural('booking', $stats['pending_bookings']) }} waiting for approval</span>
                </a>
            @endif
            @if ($lowStock->isNotEmpty())
                <span class="flex items-center gap-3 rounded-2xl bg-rose-400/10 ring-1 ring-rose-400/40 px-4 py-3">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    <span class="text-sm text-rose-100"><span class="font-bold">{{ $lowStock->count() }}</span> inventory {{ \Illuminate\Support\Str::plural('item', $lowStock->count()) }} at or below reorder level</span>
                </span>
            @endif
        </div>
    @endif

    <!-- KPIs -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @php $rt = $trend($stats['month_revenue_change']); $bt = $trend($stats['month_bookings_change']); $ut = $trend($stats['new_users_change']); @endphp
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Revenue this month</p>
            <p class="mt-2 text-3xl font-bold text-white">₱{{ number_format($stats['month_revenue']) }}</p>
            <p class="mt-1 text-xs sm:text-sm {{ $rt['class'] }}">{{ $rt['text'] }}</p>
        </div>
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Bookings this month</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $stats['month_bookings'] }}</p>
            <p class="mt-1 text-xs sm:text-sm {{ $bt['class'] }}">{{ $bt['text'] }}</p>
        </div>
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">New users this month</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $stats['new_users'] }}</p>
            <p class="mt-1 text-xs sm:text-sm {{ $ut['class'] }}">{{ $ut['text'] }}</p>
        </div>
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Services offered</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $stats['active_services'] }}<span class="text-lg text-gray-500">/{{ $stats['total_services'] }}</span></p>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ $stats['total_services'] - $stats['active_services'] }} hidden · {{ $stats['total_inventory'] }} stock items</p>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3 items-start">
        <!-- Accounts -->
        <section class="cp-card p-5 sm:p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-base font-bold text-white">Accounts</h2>
                <span class="text-2xl font-bold text-white">{{ $stats['total_users'] }}</span>
            </div>
            <ul class="mt-4 space-y-3">
                @foreach ($roleRows as $name => [$label, $color])
                    @php $count = $roles[$name]->users_count ?? 0; @endphp
                    <li>
                        <div class="flex justify-between text-sm"><span class="flex items-center gap-2 text-gray-300"><span class="w-2.5 h-2.5 rounded-full {{ $color }}"></span>{{ $label }}</span><span class="font-semibold text-white tabular-nums">{{ $count }}</span></div>
                        <div class="mt-1.5 h-1.5 rounded-full bg-white/[0.06] overflow-hidden"><div class="h-full rounded-full {{ $color }}" style="width: {{ $stats['total_users'] ? $count / $stats['total_users'] * 100 : 0 }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </section>

        <!-- Bookings by status -->
        <section class="cp-card p-5 sm:p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-base font-bold text-white">All bookings by status</h2>
                <span class="text-2xl font-bold text-white">{{ $bookingsByStatus->sum() }}</span>
            </div>
            <div class="mt-4 flex h-3 w-full overflow-hidden rounded-full bg-white/[0.06] gap-[2px]">
                @foreach ($statusBars as $key => [$label, $color])
                    @if (($bookingsByStatus[$key] ?? 0) > 0)
                        <span class="{{ $color }} h-full" style="width: {{ $bookingsByStatus[$key] / $statusTotal * 100 }}%" title="{{ $label }}: {{ $bookingsByStatus[$key] }}"></span>
                    @endif
                @endforeach
            </div>
            <ul class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                @foreach ($statusBars as $key => [$label, $color])
                    <li class="flex items-center justify-between"><span class="flex items-center gap-2 text-gray-300"><span class="w-2.5 h-2.5 rounded-full {{ $color }}"></span>{{ $label }}</span><span class="font-semibold text-white tabular-nums">{{ $bookingsByStatus[$key] ?? 0 }}</span></li>
                @endforeach
            </ul>
        </section>

        <!-- Low stock -->
        <section class="cp-card p-5 sm:p-6">
            <h2 class="text-base font-bold text-white">Low stock</h2>
            <p class="text-sm text-gray-400">Items at or below their reorder level</p>
            <ul class="mt-4 space-y-2">
                @forelse ($lowStock as $item)
                    <li class="flex items-center justify-between rounded-xl bg-black/20 ring-1 ring-white/[0.06] px-3 py-2.5 text-sm">
                        <span class="min-w-0"><span class="block text-white truncate">{{ $item->name }}</span><span class="block text-xs text-gray-500">{{ $item->sku }}</span></span>
                        <span class="text-right"><span class="block font-semibold text-rose-300 tabular-nums">{{ $item->quantity_in_stock }} left</span><span class="block text-xs text-gray-500">reorder at {{ $item->reorder_level }}</span></span>
                    </li>
                @empty
                    <li class="flex items-center gap-2 py-6 justify-center text-sm text-emerald-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        All stock levels are fine
                    </li>
                @endforelse
            </ul>
        </section>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 items-start">
        <!-- Activity -->
        <section class="cp-card p-5 sm:p-6">
            <h2 class="text-base font-bold text-white">Recent activity</h2>
            <p class="text-sm text-gray-400">Booking status changes by owners and admins</p>
            <ul class="mt-4 space-y-3">
                @forelse ($recentActivity as $log)
                    <li class="flex items-start gap-3 text-sm">
                        <span class="mt-1.5 w-2 h-2 rounded-full bg-sky-400 flex-shrink-0"></span>
                        <span class="min-w-0 flex-1">
                            <span class="text-gray-200"><span class="font-semibold text-white">{{ $log->user?->name ?? 'System' }}</span> set
                                @if ($log->booking)
                                    <a href="{{ route('admin.booking.detail', $log->booking_id) }}" wire:navigate class="text-sky-300 hover:text-sky-200">#{{ str_pad($log->booking_id, 4, '0', STR_PAD_LEFT) }}</a>
                                    ({{ $log->booking->customer?->getDisplayName() }})
                                @else
                                    a deleted booking
                                @endif
                                to <span class="font-semibold text-white">{{ ucwords(str_replace('_', ' ', $log->new_status)) }}</span></span>
                            <span class="block text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                        </span>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-500">No activity yet.</li>
                @endforelse
            </ul>
        </section>

        <div class="space-y-6">
            <!-- New users -->
            <section class="cp-card p-5 sm:p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-base font-bold text-white">Newest accounts</h2>
                    <a href="{{ route('admin.users') }}" wire:navigate class="text-xs font-semibold text-sky-300 hover:text-sky-200">All users →</a>
                </div>
                <ul class="mt-4 divide-y divide-white/[0.05]">
                    @foreach ($recentUsers as $user)
                        <li class="flex items-center gap-3 py-2.5">
                            <span class="w-8 h-8 rounded-full bg-white/[0.06] flex items-center justify-center text-sm font-bold text-gray-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            <span class="min-w-0 flex-1"><span class="block text-sm font-medium text-white truncate">{{ $user->name }}</span><span class="block text-xs text-gray-500 truncate">{{ $user->email }}</span></span>
                            <span class="text-right text-xs"><span class="block text-gray-300">{{ $roleLabel($user->role?->name) }}</span><span class="block text-gray-500">{{ $user->created_at->diffForHumans() }}</span></span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <!-- Upcoming -->
            <section class="cp-card p-5 sm:p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-base font-bold text-white">Coming up (next 7 days)</h2>
                    <a href="{{ route('admin.bookings') }}" wire:navigate class="text-xs font-semibold text-sky-300 hover:text-sky-200">All bookings →</a>
                </div>
                <ul class="mt-4 space-y-1">
                    @forelse ($upcomingBookings as $booking)
                        <li>
                            <a href="{{ route('admin.booking.detail', $booking->id) }}" wire:navigate class="flex items-center gap-3 rounded-xl p-2.5 -mx-2.5 hover:bg-white/[0.04] transition">
                                <span class="w-11 flex-shrink-0 rounded-lg bg-white/[0.05] ring-1 ring-white/10 text-center py-1">
                                    <span class="block text-[10px] font-semibold uppercase text-gray-400">{{ $booking->booking_date->format('M') }}</span>
                                    <span class="block text-base font-bold text-white leading-none">{{ $booking->booking_date->format('d') }}</span>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-medium text-white truncate">{{ $booking->customer->getDisplayName() }}</span>
                                    <span class="block text-xs text-gray-400 truncate">{{ $booking->booking_time->format('g:i A') }} · {{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</span>
                                </span>
                                <x-status-badge :status="$booking->status" class="!px-2 !py-0.5" />
                            </a>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-gray-500">Nothing booked for the coming week.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</div>
