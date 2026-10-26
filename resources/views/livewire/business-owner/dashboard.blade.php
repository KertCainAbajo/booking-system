@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first();
    $peso = fn ($v, $d = 0) => '₱' . number_format($v, $d);

    // Chart scale: round the top of the axis up to a 1 / 2 / 5 step so gridlines land on clean numbers
    $peak = max(1000, $monthlyRevenue->max('revenue'));
    $magnitude = 10 ** floor(log10($peak));
    $axisMax = collect([1, 2, 5, 10])->map(fn ($m) => $m * $magnitude)->first(fn ($v) => $v >= $peak);
    // Quarters for 1x/2x tops, fifths for 5x tops, so every gridline is a round number
    $steps = (int) round($axisMax / $magnitude) === 5 ? 5 : 4;
    $ticks = collect(range($steps, 0))->map(fn ($i) => $axisMax * $i / $steps);
    $shortPeso = fn ($v) => $v >= 1000 ? '₱' . rtrim(rtrim(number_format($v / 1000, 1), '0'), '.') . 'k' : '₱' . number_format($v);

    $topServices = $topServices->where('times_booked', '>', 0);
    $topMax = max(1, $topServices->max('times_booked'));

    $trend = function (?float $change) {
        if ($change === null) {
            return ['text' => 'No data last month', 'class' => 'text-gray-500'];
        }
        if ($change == 0) {
            return ['text' => 'Same as last month', 'class' => 'text-gray-500'];
        }
        return [
            'text' => ($change > 0 ? '▲ ' : '▼ ') . abs($change) . '% vs last month',
            'class' => $change > 0 ? 'text-emerald-300' : 'text-rose-300',
        ];
    };
    $revenueTrend = $trend($stats['month_revenue_change']);
    $bookingsTrend = $trend($stats['month_bookings_change']);
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">{{ now()->format('l, F j, Y') }}</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">{{ $greeting }}, {{ $firstName }}</h1>
            <p class="mt-1 text-gray-400">Here's how the shop is doing in {{ now()->format('F') }}.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('owner.today') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-emerald-400 text-gray-950 text-sm font-semibold hover:bg-emerald-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Today's work
            </a>
            <a href="{{ route('owner.reports') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-white/[0.05] ring-1 ring-white/10 text-sm font-semibold text-gray-200 hover:bg-white/10 transition">
                Reports
            </a>
        </div>
    </div>

    <!-- Needs attention -->
    @if ($stats['pending_bookings'] > 0 || $stats['pending_payments'] > 0)
        <div class="flex flex-wrap gap-3">
            @if ($stats['pending_bookings'] > 0)
                <a href="{{ route('owner.bookings') }}" wire:navigate class="flex items-center gap-3 rounded-2xl bg-amber-400/10 ring-1 ring-amber-400/40 px-4 py-3 hover:ring-amber-400/70 transition">
                    <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm text-amber-100"><span class="font-bold">{{ $stats['pending_bookings'] }}</span> {{ \Illuminate\Support\Str::plural('booking', $stats['pending_bookings']) }} waiting for your approval</span>
                    <span class="text-sm font-semibold text-amber-300">Review →</span>
                </a>
            @endif
            @if ($stats['pending_payments'] > 0)
                <div class="flex items-center gap-3 rounded-2xl bg-white/[0.03] ring-1 ring-white/10 px-4 py-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="text-sm text-gray-300"><span class="font-bold text-white">{{ $peso($stats['pending_payments'], 2) }}</span> in unpaid payments</span>
                </div>
            @endif
        </div>
    @endif

    <!-- KPIs -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="cp-card p-4 sm:p-5 col-span-2 lg:col-span-1 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-emerald-500/15 blur-2xl" aria-hidden="true"></div>
            <p class="relative text-sm font-medium text-gray-400">Revenue this month</p>
            <p class="relative mt-2 text-3xl sm:text-4xl font-bold text-white">{{ $peso($stats['month_revenue']) }}</p>
            <p class="relative mt-1 text-xs sm:text-sm {{ $revenueTrend['class'] }}">{{ $revenueTrend['text'] }}</p>
        </div>
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Revenue today</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $peso($stats['today_revenue']) }}</p>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ $stats['today_bookings'] }} {{ \Illuminate\Support\Str::plural('booking', $stats['today_bookings']) }} today</p>
        </div>
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Bookings this month</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $stats['month_bookings'] }}</p>
            <p class="mt-1 text-xs sm:text-sm {{ $bookingsTrend['class'] }}">{{ $bookingsTrend['text'] }}</p>
        </div>
        <div class="cp-card p-4 sm:p-5 col-span-2 lg:col-span-1">
            <p class="text-sm font-medium text-gray-400">Average job value</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $peso($stats['avg_job_value']) }}</p>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ $stats['month_completed'] }} completed · {{ $stats['cancel_rate'] }}% cancelled</p>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_24rem] items-start">
        <!-- Revenue chart -->
        <section class="cp-card p-5 sm:p-7" x-data="{ active: null, asTable: false }">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-white">Revenue, last 6 months</h2>
                    <p class="text-sm text-gray-400">Completed work and paid payments per month</p>
                </div>
                <button type="button" x-on:click="asTable = !asTable" class="text-sm font-semibold text-emerald-300 hover:text-emerald-200" x-text="asTable ? 'Show chart' : 'Show as table'">Show as table</button>
            </div>

            <!-- Chart -->
            <div x-show="!asTable" class="mt-6">
                <div class="relative h-64 pl-12">
                    <!-- Gridlines + y labels -->
                    @foreach ($ticks as $tick)
                        <div class="absolute left-12 right-0 border-t {{ $loop->last ? 'border-white/20' : 'border-white/[0.06]' }}" style="bottom: {{ $tick / $axisMax * 100 }}%">
                            <span class="absolute -left-12 -translate-y-1/2 w-10 text-right text-xs text-gray-500 tabular-nums">{{ $shortPeso($tick) }}</span>
                        </div>
                    @endforeach

                    <!-- Bars -->
                    <div class="absolute inset-0 left-12 flex items-end gap-[2px] px-2 sm:px-4">
                        @foreach ($monthlyRevenue as $i => $month)
                            @php $height = $month['revenue'] / $axisMax * 100; @endphp
                            <div class="relative flex-1 h-full flex items-end justify-center"
                                 x-on:mouseenter="active = {{ $i }}" x-on:mouseleave="active = null"
                                 x-on:focus="active = {{ $i }}" x-on:blur="active = null" tabindex="0"
                                 aria-label="{{ $month['full_label'] }}: {{ $peso($month['revenue'], 2) }}, {{ $month['jobs'] }} completed jobs">
                                <div class="w-full max-w-[3.5rem] rounded-t-[4px] transition-opacity {{ $month['is_current'] ? 'bg-emerald-400' : 'bg-emerald-500' }}"
                                     :class="active !== null && active !== {{ $i }} ? 'opacity-40' : ''"
                                     style="height: {{ max($height, $month['revenue'] > 0 ? 1.5 : 0) }}%"></div>

                                <!-- Tooltip -->
                                <div x-show="active === {{ $i }}" x-transition.opacity.duration.100ms style="display: none; bottom: calc({{ min($height, 80) }}% + 0.5rem);"
                                     class="absolute z-10 w-max rounded-xl bg-[#1b2220] ring-1 ring-white/15 shadow-xl px-3 py-2 text-left pointer-events-none">
                                    <p class="text-xs text-gray-400">{{ $month['full_label'] }}</p>
                                    <p class="text-sm font-bold text-white">{{ $peso($month['revenue'], 2) }}</p>
                                    <p class="text-xs text-gray-400">{{ $month['jobs'] }} completed {{ \Illuminate\Support\Str::plural('job', $month['jobs']) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- X labels -->
                <div class="flex gap-[2px] pl-12 mt-2 px-2 sm:px-4">
                    @foreach ($monthlyRevenue as $month)
                        <span class="flex-1 text-center text-xs {{ $month['is_current'] ? 'font-semibold text-white' : 'text-gray-500' }}">{{ $month['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Table view -->
            <div x-show="asTable" style="display: none;" class="mt-6 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 border-b border-white/10">
                            <th class="py-2 font-medium">Month</th>
                            <th class="py-2 font-medium text-right">Revenue</th>
                            <th class="py-2 font-medium text-right">Completed jobs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthlyRevenue->reverse() as $month)
                            <tr class="border-b border-white/[0.05]">
                                <td class="py-2.5 text-gray-200">{{ $month['full_label'] }}</td>
                                <td class="py-2.5 text-right text-white font-semibold tabular-nums">{{ $peso($month['revenue'], 2) }}</td>
                                <td class="py-2.5 text-right text-gray-300 tabular-nums">{{ $month['jobs'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Top services -->
        <section class="cp-card p-5 sm:p-6">
            <h2 class="text-base font-bold text-white">Most booked services</h2>
            <p class="text-sm text-gray-400">Times booked, cancelled bookings not counted</p>
            <ul class="mt-5 space-y-4">
                @forelse ($topServices as $service)
                    <li>
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-white truncate">{{ $service->name }}</span>
                                <span class="block text-xs text-gray-500 truncate">{{ $service->category?->name }}</span>
                            </span>
                            <span class="text-right flex-shrink-0">
                                <span class="block text-sm font-bold text-white tabular-nums">{{ $service->times_booked }}×</span>
                                <span class="block text-xs text-gray-500 tabular-nums">{{ $peso($service->earned ?? 0) }} earned</span>
                            </span>
                        </div>
                        <div class="mt-2 h-1.5 rounded-full bg-white/[0.06] overflow-hidden" title="{{ $service->times_booked }} bookings">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $service->times_booked / $topMax * 100 }}%"></div>
                        </div>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-500">No services booked yet.</li>
                @endforelse
            </ul>
        </section>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 items-start">
        <!-- Recent bookings -->
        <section class="cp-card p-5 sm:p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-base font-bold text-white">Latest bookings</h2>
                <a href="{{ route('owner.bookings') }}" wire:navigate class="text-xs font-semibold text-emerald-300 hover:text-emerald-200">All bookings →</a>
            </div>
            <ul class="mt-4 divide-y divide-white/[0.06]">
                @forelse ($recentBookings as $booking)
                    <li>
                        <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="flex items-center gap-3 py-3 hover:bg-white/[0.02] -mx-2 px-2 rounded-xl transition">
                            <span class="w-9 h-9 flex-shrink-0 rounded-full bg-white/[0.06] flex items-center justify-center text-sm font-bold text-gray-300">
                                {{ strtoupper(substr($booking->customer->getDisplayName(), 0, 1)) }}
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-medium text-white truncate">{{ $booking->customer->getDisplayName() }}</span>
                                <span class="block text-xs text-gray-400 truncate">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }} · {{ $booking->booking_date->format('M j') }}, {{ $booking->booking_time->format('g:i A') }}</span>
                            </span>
                            <span class="text-right flex-shrink-0">
                                <x-status-badge :status="$booking->status" class="!px-2 !py-0.5" />
                                <span class="block mt-1 text-xs font-semibold text-gray-300 tabular-nums">{{ $peso($booking->total_amount) }}</span>
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-gray-500">No bookings yet.</li>
                @endforelse
            </ul>
        </section>

        <!-- Coming up -->
        <section class="cp-card p-5 sm:p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-base font-bold text-white">Coming up (next 7 days)</h2>
                <a href="{{ route('owner.calendar') }}" wire:navigate class="text-xs font-semibold text-emerald-300 hover:text-emerald-200">Calendar →</a>
            </div>
            <ul class="mt-4 space-y-1">
                @forelse ($upcomingBookings as $booking)
                    <li>
                        <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="flex items-center gap-3 rounded-xl p-2.5 -mx-2.5 hover:bg-white/[0.04] transition">
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
                    <li class="py-8 text-center text-sm text-gray-500">Nothing booked for the coming week.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
