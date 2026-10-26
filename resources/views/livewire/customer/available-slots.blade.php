<section class="rounded-3xl bg-white/[0.03] ring-1 ring-white/[0.07] p-5 sm:p-7">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Open appointment slots</h2>
            <p class="text-sm text-gray-400">Pick a day to see which times are still free.</p>
        </div>
        <a href="{{ route('customer.book') }}" wire:navigate class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-emerald-500 text-gray-950 font-semibold hover:bg-emerald-400 transition">
            Book now
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>

    <!-- Date Selection -->
    <div class="flex gap-2.5 overflow-x-auto pb-2 -mx-1 px-1 snap-x">
        @foreach($availableDates as $dateInfo)
            @php $isSelected = $selectedDate === $dateInfo['date']; @endphp
            <button
                wire:click="selectDate('{{ $dateInfo['date'] }}')"
                @class([
                    'snap-start flex-shrink-0 w-[5.5rem] sm:flex-1 sm:w-auto sm:min-w-[5.5rem] rounded-2xl py-3 px-2 text-center ring-1 transition',
                    'bg-emerald-500 text-gray-950 ring-emerald-400 shadow-lg shadow-emerald-900/40' => $isSelected,
                    'bg-rose-500/[0.06] ring-rose-500/30 text-rose-300' => ! $isSelected && $dateInfo['isFullyBooked'],
                    'bg-white/[0.03] ring-white/10 text-gray-200 hover:ring-emerald-400/40 hover:bg-white/[0.06]' => ! $isSelected && ! $dateInfo['isFullyBooked'],
                ])>
                <div @class(['text-xs font-semibold uppercase tracking-wide', 'text-gray-900/70' => $isSelected, 'text-emerald-300' => ! $isSelected && $dateInfo['isToday'], 'text-gray-400' => ! $isSelected && ! $dateInfo['isToday']])>
                    {{ $dateInfo['isToday'] ? 'Today' : \Illuminate\Support\Str::limit($dateInfo['day'], 3, '') }}
                </div>
                <div class="mt-1 text-2xl font-bold leading-none">{{ $dateInfo['dayNum'] }}</div>
                <div @class(['mt-1 text-xs font-medium uppercase', 'text-gray-900/70' => $isSelected, 'text-gray-500' => ! $isSelected])>
                    {{ $dateInfo['month'] }}
                </div>
                @if($dateInfo['isFullyBooked'])
                    <div class="mt-1.5 text-[10px] font-bold uppercase tracking-wider">Full</div>
                @endif
            </button>
        @endforeach
    </div>

    <!-- Time Slots -->
    @if($selectedDate)
        <div class="mt-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <h3 class="text-base font-semibold text-white">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j') }}
                </h3>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400"></span>Open</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Almost full</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-400"></span>Full</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5">
                @forelse($selectedDateSlots as $slot)
                    <div @class([
                        'relative rounded-xl px-4 py-3.5 ring-1 transition',
                        'bg-rose-500/[0.05] ring-rose-500/20 opacity-60' => $slot['isFull'],
                        'bg-amber-400/[0.06] ring-amber-400/30' => ! $slot['isFull'] && $slot['isLimited'],
                        'bg-emerald-400/[0.05] ring-emerald-400/25 hover:ring-emerald-400/60' => ! $slot['isFull'] && ! $slot['isLimited'],
                    ])>
                        <div class="flex items-center justify-between gap-2">
                            <span @class([
                                'text-lg font-bold tabular-nums',
                                'text-rose-300 line-through decoration-rose-400/60' => $slot['isFull'],
                                'text-amber-200' => ! $slot['isFull'] && $slot['isLimited'],
                                'text-white' => ! $slot['isFull'] && ! $slot['isLimited'],
                            ])>{{ $slot['displayTime'] }}</span>
                            <span @class([
                                'w-2 h-2 rounded-full',
                                'bg-rose-400' => $slot['isFull'],
                                'bg-amber-400 animate-pulse' => ! $slot['isFull'] && $slot['isLimited'],
                                'bg-emerald-400' => ! $slot['isFull'] && ! $slot['isLimited'],
                            ])></span>
                        </div>
                        <p @class([
                            'mt-1 text-xs font-medium',
                            'text-rose-300' => $slot['isFull'],
                            'text-amber-300' => ! $slot['isFull'] && $slot['isLimited'],
                            'text-gray-400' => ! $slot['isFull'] && ! $slot['isLimited'],
                        ])>
                            @if($slot['isFull'])
                                Fully booked
                            @else
                                {{ $slot['availableSpots'] }} {{ \Illuminate\Support\Str::plural('spot', $slot['availableSpots']) }} left
                            @endif
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 rounded-2xl border border-dashed border-white/10 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No open times on this day. Try another date.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Info Note -->
    <div class="mt-6 rounded-2xl bg-white/[0.03] ring-1 ring-white/[0.06] p-4 flex gap-3">
        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-gray-400">
            <p class="font-semibold text-gray-200 mb-1">Good to know</p>
            <ul class="grid sm:grid-cols-2 gap-x-6 gap-y-1">
                <li>Open 8:00 AM – 6:00 PM, closed Sundays</li>
                <li>Lunch break 12:00 – 1:00 PM</li>
                <li>One car per time slot</li>
                <li>Popular times fill up fast, so book early</li>
            </ul>
        </div>
    </div>
</section>
