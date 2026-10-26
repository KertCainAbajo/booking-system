<div class="space-y-6">
    <!-- Page header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">Service History</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Past services</h1>
            <p class="mt-1 text-gray-400">Every visit, what was done, and what you paid.</p>
        </div>
        <a href="{{ route('customer.book') }}" wire:navigate class="cp-btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Book a service
        </a>
    </div>

    @forelse($historyBookings as $booking)
        <article class="cp-card overflow-hidden" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
            <!-- Summary row (click to expand) -->
            <button type="button" @click="open = !open" class="w-full text-left p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-white/[0.02] transition" :aria-expanded="open.toString()">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-16 flex-shrink-0 rounded-xl bg-white/[0.05] ring-1 ring-white/10 text-center py-2">
                        <div class="text-[11px] font-semibold uppercase text-gray-400">{{ $booking->booking_date->format('M Y') }}</div>
                        <div class="text-2xl font-bold text-white leading-none">{{ $booking->booking_date->format('d') }}</div>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-lg font-semibold text-white">Service #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <x-status-badge :status="$booking->status" />
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                            <span>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</span>
                            <span class="plate-number text-xs">{{ $booking->vehicle->plate_number }}</span>
                            <span>· {{ $booking->booking_time->format('g:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-4">
                    <div class="text-right">
                        <div class="text-xs text-gray-500">{{ $booking->status === 'completed' ? 'Total paid' : 'Total' }}</div>
                        <div class="text-xl font-bold text-white">₱{{ number_format($booking->total_amount, 2) }}</div>
                    </div>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>

            <!-- Details -->
            <div x-show="open" x-collapse class="border-t border-white/[0.06]">
                <div class="p-5 sm:p-6 grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">
                    <!-- Services performed -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 mb-3">Work performed</h3>
                        <ul class="cp-panel divide-y divide-white/[0.06]">
                            @foreach($booking->services as $service)
                                <li class="flex items-center justify-between gap-4 px-4 py-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-8 h-8 flex-shrink-0 rounded-lg bg-emerald-400/10 text-emerald-300 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-medium text-white truncate">{{ $service->name }}</div>
                                            @if($service->estimated_duration)
                                                <div class="text-xs text-gray-500">About {{ $service->estimated_duration }} min</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="font-semibold text-gray-200 tabular-nums">₱{{ number_format($service->pivot->price, 2) }}</div>
                                </li>
                            @endforeach
                        </ul>

                        @if($booking->notes)
                            <div class="mt-5">
                                <h3 class="text-sm font-semibold text-gray-300 mb-2">Notes</h3>
                                <p class="cp-panel p-4 text-gray-300">{{ $booking->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Side: vehicle + payment -->
                    <div class="space-y-4">
                        <div class="cp-panel p-4">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">Vehicle</h3>
                            <p class="text-lg font-semibold text-white">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                            <p class="text-sm text-gray-400">{{ $booking->vehicle->year }} model</p>
                            <span class="plate-number text-sm inline-block mt-3">{{ $booking->vehicle->plate_number }}</span>
                        </div>

                        @if($booking->payment)
                            <div class="cp-panel p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-semibold text-gray-300">Payment</h3>
                                    <span class="cp-badge bg-emerald-400/10 text-emerald-300 ring-emerald-400/30">{{ ucfirst($booking->payment->payment_status) }}</span>
                                </div>
                                <dl class="space-y-1.5 text-sm">
                                    <div class="flex justify-between"><dt class="text-gray-400">Method</dt><dd class="text-white font-medium">{{ ucwords(str_replace('_', ' ', $booking->payment->payment_method)) }}</dd></div>
                                    <div class="flex justify-between"><dt class="text-gray-400">Date</dt><dd class="text-white font-medium">{{ $booking->payment->payment_date->format('M d, Y') }}</dd></div>
                                </dl>
                            </div>
                        @endif

                        <div class="rounded-2xl bg-emerald-400/[0.07] ring-1 ring-emerald-400/20 p-4 flex items-center justify-between">
                            <span class="font-medium text-emerald-200">{{ $booking->status === 'completed' ? 'Total paid' : 'Total' }}</span>
                            <span class="text-2xl font-bold text-white">₱{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="cp-card text-center px-6 py-16">
            <span class="mx-auto w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <h2 class="mt-5 text-xl font-bold text-white">No past services yet</h2>
            <p class="mt-2 text-gray-400">Once a service is finished, it will appear here with all the details.</p>
            <a href="{{ route('customer.book') }}" wire:navigate class="cp-btn-primary mt-6">
                Book your first service
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    @endforelse
</div>
