@php
    $peso = fn ($v, $d = 0) => '₱' . number_format((float) $v, $d);
    $presets = ['this_month' => 'This month', 'last_month' => 'Last month', 'last_3_months' => 'Last 3 months', 'this_year' => 'This year'];

    $compare = function ($now, $before, $lowerIsBetter = false) {
        if ($before == 0) {
            return ['text' => $now > 0 ? 'New this period' : 'No change', 'class' => 'text-gray-500'];
        }
        $change = round(($now - $before) / $before * 100, 1);
        if ($change == 0) {
            return ['text' => 'Same as before', 'class' => 'text-gray-500'];
        }
        $good = $lowerIsBetter ? $change < 0 : $change > 0;
        return ['text' => ($change > 0 ? '▲ ' : '▼ ') . abs($change) . '%', 'class' => $good ? 'text-emerald-300' : 'text-rose-300'];
    };

    // Chart scale: round the axis top up to a 1 / 2 / 5 step
    $peak = max(1000, $series->max('revenue'));
    $magnitude = 10 ** floor(log10($peak));
    $axisMax = collect([1, 2, 5, 10])->map(fn ($m) => $m * $magnitude)->first(fn ($v) => $v >= $peak);
    // Quarters for 1x/2x tops, fifths for 5x tops, so every gridline is a round number
    $steps = (int) round($axisMax / $magnitude) === 5 ? 5 : 4;
    $ticks = collect(range($steps, 0))->map(fn ($i) => $axisMax * $i / $steps);
    $shortPeso = fn ($v) => $v >= 1000 ? '₱' . rtrim(rtrim(number_format($v / 1000, 1), '0'), '.') . 'k' : '₱' . number_format($v);
    $labelEvery = $series->count() > 16 ? (int) ceil($series->count() / 10) : 1;

    $categoryTotal = max(1, $byCategory->sum('revenue'));
    $topServiceMax = max(1, $topServices->max('revenue'));
    $weekdayMax = max(1, $weekdays->max('count'));

    $kpis = [
        ['label' => 'Revenue', 'value' => $peso($current['revenue']), 'cmp' => $compare($current['revenue'], $previous['revenue'])],
        ['label' => 'Completed jobs', 'value' => $current['completed'], 'cmp' => $compare($current['completed'], $previous['completed'])],
        ['label' => 'Average job value', 'value' => $peso($current['avg_job']), 'cmp' => $compare($current['avg_job'], $previous['avg_job'])],
        ['label' => 'Bookings', 'value' => $current['bookings'], 'cmp' => $compare($current['bookings'], $previous['bookings'])],
        ['label' => 'Cancelled / no-show', 'value' => $current['lost'] . ' (' . $current['lost_rate'] . '%)', 'cmp' => $compare($current['lost'], $previous['lost'], true)],
    ];
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">Reports</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Revenue report</h1>
            <p class="mt-1 text-gray-400">{{ $rangeFrom->format('M j, Y') }} – {{ $rangeTo->format('M j, Y') }} · {{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}</p>
        </div>
        <button wire:click="exportCsv" wire:loading.attr="disabled" wire:target="exportCsv" class="cp-btn-ghost">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download CSV
        </button>
    </div>

    <!-- Period -->
    <section class="cp-card p-4 sm:p-5 flex flex-wrap items-end gap-3">
        <div class="flex flex-wrap gap-2">
            @foreach ($presets as $value => $label)
                <button wire:click="$set('period', '{{ $value }}')"
                        @class([
                            'h-10 px-4 rounded-xl text-sm font-semibold ring-1 transition',
                            'bg-emerald-400 text-gray-950 ring-emerald-400' => $period === $value,
                            'bg-white/[0.04] text-gray-300 ring-white/10 hover:text-white hover:bg-white/10' => $period !== $value,
                        ])>{{ $label }}</button>
            @endforeach
        </div>
        <div class="flex items-end gap-2 ml-auto">
            <div>
                <label for="reportFrom" class="cp-label">From</label>
                <input id="reportFrom" type="date" wire:model.live="from" class="cp-input !min-h-[2.5rem] !py-1.5">
            </div>
            <div>
                <label for="reportTo" class="cp-label">To</label>
                <input id="reportTo" type="date" wire:model.live="to" class="cp-input !min-h-[2.5rem] !py-1.5">
            </div>
        </div>
    </section>

    <!-- KPIs -->
    <section class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        @foreach ($kpis as $kpi)
            <div @class(['cp-card p-4 sm:p-5', 'col-span-2 lg:col-span-1' => $loop->first])>
                <p class="text-sm font-medium text-gray-400">{{ $kpi['label'] }}</p>
                <p class="mt-2 text-2xl sm:text-3xl font-bold text-white">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-xs sm:text-sm {{ $kpi['cmp']['class'] }}">{{ $kpi['cmp']['text'] }}</p>
            </div>
        @endforeach
    </section>
    <p class="-mt-3 text-xs text-gray-500">Compared with the {{ $days }} days before ({{ $prevFrom->format('M j') }} – {{ $prevTo->format('M j, Y') }}).</p>

    <!-- Revenue over time -->
    <section class="cp-card p-5 sm:p-7" x-data="{ active: null, asTable: false }">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-white">Revenue {{ $byDay ? 'per day' : 'per month' }}</h2>
                <p class="text-sm text-gray-400">Completed work and paid payments</p>
            </div>
            <button type="button" x-on:click="asTable = !asTable" class="text-sm font-semibold text-emerald-300 hover:text-emerald-200" x-text="asTable ? 'Show chart' : 'Show as table'">Show as table</button>
        </div>

        <div x-show="!asTable" class="mt-6">
            <div class="relative h-64 pl-12">
                @foreach ($ticks as $tick)
                    <div class="absolute left-12 right-0 border-t {{ $loop->last ? 'border-white/20' : 'border-white/[0.06]' }}" style="bottom: {{ $tick / $axisMax * 100 }}%">
                        <span class="absolute -left-12 -translate-y-1/2 w-10 text-right text-xs text-gray-500 tabular-nums">{{ $shortPeso($tick) }}</span>
                    </div>
                @endforeach
                <div class="absolute inset-0 left-12 flex items-end gap-[2px] px-1">
                    @foreach ($series as $i => $point)
                        @php $height = $point['revenue'] / $axisMax * 100; @endphp
                        <div class="relative flex-1 h-full flex items-end justify-center"
                             x-on:mouseenter="active = {{ $i }}" x-on:mouseleave="active = null"
                             x-on:focus="active = {{ $i }}" x-on:blur="active = null" tabindex="0"
                             aria-label="{{ $point['full_label'] }}: {{ $peso($point['revenue'], 2) }}">
                            <div class="w-full max-w-[3rem] rounded-t-[4px] bg-emerald-500 transition-opacity"
                                 :class="active !== null && active !== {{ $i }} ? 'opacity-40' : ''"
                                 style="height: {{ max($height, $point['revenue'] > 0 ? 1.5 : 0) }}%"></div>
                            <div x-show="active === {{ $i }}" x-transition.opacity.duration.100ms style="display: none; bottom: calc({{ min($height, 80) }}% + 0.5rem);"
                                 class="absolute z-10 w-max rounded-xl bg-[#1b2220] ring-1 ring-white/15 shadow-xl px-3 py-2 pointer-events-none">
                                <p class="text-xs text-gray-400">{{ $point['full_label'] }}</p>
                                <p class="text-sm font-bold text-white">{{ $peso($point['revenue'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-[2px] pl-12 mt-2 px-1">
                @foreach ($series as $i => $point)
                    <span class="flex-1 text-center text-[11px] text-gray-500">{{ $i % $labelEvery === 0 ? $point['label'] : '' }}</span>
                @endforeach
            </div>
        </div>

        <div x-show="asTable" style="display: none;" class="mt-6 max-h-96 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-[#121614]">
                    <tr class="text-left text-gray-400 border-b border-white/10">
                        <th class="py-2 font-medium">{{ $byDay ? 'Day' : 'Month' }}</th>
                        <th class="py-2 font-medium text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($series as $point)
                        <tr class="border-b border-white/[0.05]">
                            <td class="py-2 text-gray-200">{{ $point['full_label'] }}</td>
                            <td class="py-2 text-right font-semibold text-white tabular-nums">{{ $peso($point['revenue'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-2 items-start">
        <!-- By category -->
        <section class="cp-card p-5 sm:p-6">
            <h2 class="text-lg font-bold text-white">Revenue by category</h2>
            <p class="text-sm text-gray-400">Completed work only</p>
            <ul class="mt-5 space-y-4">
                @forelse ($byCategory as $row)
                    @php $share = round($row->revenue / $categoryTotal * 100); @endphp
                    <li>
                        <div class="flex items-baseline justify-between gap-3 text-sm">
                            <span class="font-medium text-white">{{ $row->name }}</span>
                            <span class="text-gray-300 tabular-nums"><span class="font-semibold text-white">{{ $peso($row->revenue) }}</span> · {{ $share }}%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-white/[0.06] overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $share }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ $row->jobs }} {{ \Illuminate\Support\Str::plural('service', $row->jobs) }} done</p>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-gray-500">No completed work in this period.</li>
                @endforelse
            </ul>
        </section>

        <!-- Top services -->
        <section class="cp-card p-5 sm:p-6">
            <h2 class="text-lg font-bold text-white">Top services</h2>
            <p class="text-sm text-gray-400">By revenue, completed work only</p>
            <table class="mt-4 w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-white/[0.06]">
                        <th class="py-2 font-medium">Service</th>
                        <th class="py-2 font-medium text-right">Times</th>
                        <th class="py-2 font-medium text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topServices as $row)
                        <tr class="border-b border-white/[0.05]">
                            <td class="py-2.5 pr-3">
                                <span class="block text-white">{{ $row->name }}</span>
                                <span class="mt-1 block h-1 rounded-full bg-emerald-500/70" style="width: {{ $row->revenue / $topServiceMax * 100 }}%"></span>
                            </td>
                            <td class="py-2.5 text-right text-gray-300 tabular-nums">{{ $row->times }}</td>
                            <td class="py-2.5 text-right font-semibold text-white tabular-nums">{{ $peso($row->revenue) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-8 text-center text-gray-500">No completed work in this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    <!-- Busiest days -->
    <section class="cp-card p-5 sm:p-6">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-white">Busiest days of the week</h2>
                <p class="text-sm text-gray-400">All bookings in this period, by appointment day</p>
            </div>
        </div>
        <div class="mt-5 grid grid-cols-7 gap-2 sm:gap-3">
            @foreach ($weekdays as $wd)
                <div class="text-center" title="{{ $wd['count'] }} bookings">
                    <div class="h-28 flex items-end justify-center">
                        <div class="w-full max-w-[3rem] rounded-t-[4px] {{ $wd['count'] === $weekdays->max('count') && $wd['count'] > 0 ? 'bg-emerald-400' : 'bg-emerald-500/60' }}"
                             style="height: {{ $wd['count'] / $weekdayMax * 100 }}%"></div>
                    </div>
                    <p class="mt-2 text-sm font-semibold text-white tabular-nums">{{ $wd['count'] }}</p>
                    <p class="text-xs {{ $wd['label'] === 'Sun' ? 'text-rose-300/80' : 'text-gray-500' }}">{{ $wd['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
