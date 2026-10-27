@php
    $statusLabel = fn ($s) => ucwords(str_replace('_', ' ', (string) $s));
    $statusOptions = [
        'pending' => 'bg-amber-400/10 text-amber-200 ring-amber-400/30 hover:bg-amber-400/20',
        'approved' => 'bg-sky-400/10 text-sky-200 ring-sky-400/30 hover:bg-sky-400/20',
        'completed' => 'bg-emerald-400/10 text-emerald-200 ring-emerald-400/30 hover:bg-emerald-400/20',
        'no_show' => 'bg-orange-400/10 text-orange-200 ring-orange-400/30 hover:bg-orange-400/20',
        'not_available' => 'bg-gray-400/10 text-gray-200 ring-gray-400/30 hover:bg-gray-400/20',
        'cancelled' => 'bg-rose-400/10 text-rose-200 ring-rose-400/30 hover:bg-rose-400/20',
    ];
@endphp

<div class="space-y-6">
    <x-success-modal />

    <!-- Header -->
    <div>
        <a href="{{ route('admin.bookings') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-gray-400 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            All bookings
        </a>
        <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-bold tracking-tight text-white">Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</h1>
                    <x-status-badge :status="$booking->status" class="!text-sm !px-3" />
                    @if ($booking->is_archived)
                        <span class="cp-badge bg-white/[0.06] text-gray-300 ring-white/15">Archived {{ $booking->archived_at?->format('M j, Y') }}</span>
                    @endif
                </div>
                <p class="mt-1 text-gray-400">
                    {{ $booking->booking_date->format('l, F j, Y') }} at <span class="font-semibold text-white">{{ $booking->booking_time->format('g:i A') }}</span>
                    · received {{ $booking->created_at->diffForHumans() }}
                    @if ($booking->booking_reference) · <span class="font-mono text-xs">{{ $booking->booking_reference }}</span> @endif
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-400">Total</p>
                <p class="text-3xl font-bold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem] items-start">
        <!-- Main column -->
        <div class="space-y-6 min-w-0">
            <section class="cp-card p-5 sm:p-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</h2>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="w-11 h-11 rounded-full bg-white/[0.06] flex items-center justify-center font-bold text-gray-200">{{ strtoupper(substr($booking->customer->getDisplayName(), 0, 1)) }}</span>
                        <div class="min-w-0">
                            <p class="font-semibold text-white truncate">{{ $booking->customer->getDisplayName() }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->customer->is_guest ? 'Guest booking' : 'Registered customer' }}</p>
                        </div>
                    </div>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex gap-3"><dt class="w-16 text-gray-500">Phone</dt><dd class="text-gray-200">{{ $booking->customer->getContactPhone() }}</dd></div>
                        <div class="flex gap-3"><dt class="w-16 text-gray-500">Email</dt><dd class="text-gray-200 break-all">{{ $booking->customer->getContactEmail() }}</dd></div>
                        <div class="flex gap-3"><dt class="w-16 text-gray-500">Address</dt><dd class="text-gray-200">{{ $booking->customer->address ?: '—' }}</dd></div>
                    </dl>
                </div>
                <div class="sm:border-l sm:border-white/[0.06] sm:pl-6">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Vehicle</h2>
                    <p class="mt-3 text-lg font-semibold text-white">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</p>
                    <p class="text-sm text-gray-400">{{ $booking->vehicle?->year }} model</p>
                    @if ($booking->vehicle?->plate_number)
                        <span class="plate-number text-sm inline-block mt-3">{{ $booking->vehicle->plate_number }}</span>
                    @endif
                    <p class="mt-3 text-xs text-gray-500">VIN: <span class="font-mono text-gray-300">{{ $booking->vehicle?->vin_number ?: 'not given' }}</span></p>
                </div>
            </section>

            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-lg font-bold text-white">Services</h2>
                <ul class="mt-4 cp-panel divide-y divide-white/[0.06]">
                    @foreach ($booking->services as $service)
                        <li class="flex items-start justify-between gap-4 px-4 py-3">
                            <div class="min-w-0">
                                <p class="font-medium text-white">{{ $service->name }}</p>
                                @if ($service->description)
                                    <p class="text-sm text-gray-400">{{ $service->description }}</p>
                                @endif
                            </div>
                            <p class="font-semibold text-white tabular-nums flex-shrink-0">₱{{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}</p>
                        </li>
                    @endforeach
                    <li class="flex items-center justify-between px-4 py-4 bg-emerald-400/[0.05]">
                        <span class="font-semibold text-emerald-200">Total</span>
                        <span class="text-2xl font-bold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </li>
                </ul>
                @if ($booking->notes)
                    <h3 class="mt-6 text-sm font-semibold text-gray-300">Customer's notes</h3>
                    <p class="mt-2 cp-panel p-4 text-gray-200">{{ $booking->notes }}</p>
                @endif
            </section>

            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-lg font-bold text-white">Timeline</h2>
                <ol class="mt-4 relative border-l border-white/10 ml-2 space-y-5">
                    @foreach ($booking->statusLogs as $log)
                        <li class="ml-5">
                            <span class="absolute -left-[5px] mt-1.5 w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                            <p class="text-sm text-gray-200">
                                <span class="font-semibold text-white">{{ $log->user?->name ?? 'System' }}</span>
                                changed status from <span class="font-medium text-gray-300">{{ $statusLabel($log->old_status) }}</span>
                                to <span class="font-semibold text-white">{{ $statusLabel($log->new_status) }}</span>
                            </p>
                            @if ($log->notes)
                                <p class="mt-1.5 rounded-lg bg-black/20 ring-1 ring-white/[0.06] px-3 py-2 text-sm text-gray-300">{{ $log->notes }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500">{{ $log->created_at->format('M d, Y g:i A') }} · {{ $log->created_at->diffForHumans() }}</p>
                        </li>
                    @endforeach
                    <li class="ml-5">
                        <span class="absolute -left-[5px] mt-1.5 w-2.5 h-2.5 rounded-full bg-gray-500"></span>
                        <p class="text-sm text-gray-200">Booking received</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                    </li>
                </ol>
            </section>
        </div>

        <!-- Side column -->
        <div class="space-y-6 lg:sticky lg:top-6">
            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-lg font-bold text-white">Change status</h2>
                <p class="text-sm text-gray-400">Admin override. Every change is recorded in the timeline.</p>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach ($statusOptions as $value => $style)
                        @php $isCurrent = $booking->status === $value; @endphp
                        <button wire:click="updateStatus('{{ $value }}')"
                                @if (in_array($value, ['cancelled', 'not_available'])) wire:confirm="Set this booking to {{ $statusLabel($value) }}?" @endif
                                @disabled($isCurrent)
                                @class([
                                    'h-11 px-3 rounded-xl text-sm font-semibold ring-1 transition',
                                    $style => ! $isCurrent,
                                    'bg-white text-gray-950 ring-white cursor-default' => $isCurrent,
                                ])>
                            {{ $isCurrent ? '✓ ' : '' }}{{ $statusLabel($value) }}
                        </button>
                    @endforeach
                </div>
                <div class="mt-4">
                    <label for="adminNote" class="cp-label">Reason / note <span class="text-gray-500">(optional)</span></label>
                    <textarea id="adminNote" wire:model="notes" rows="3" class="cp-input" placeholder="Why are you changing this?"></textarea>
                </div>
            </section>

            <x-mechanic-form :booking="$booking" />

            @if ($booking->payment)
                <section class="cp-card p-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Payment</h2>
                        <span @class(['cp-badge', 'bg-emerald-400/10 text-emerald-300 ring-emerald-400/30' => $booking->payment->payment_status === 'paid', 'bg-amber-400/10 text-amber-300 ring-amber-400/30' => $booking->payment->payment_status !== 'paid'])>
                            {{ ucfirst($booking->payment->payment_status) }}
                        </span>
                    </div>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-400">Amount</dt><dd class="font-semibold text-white tabular-nums">₱{{ number_format($booking->payment->amount, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-400">Method</dt><dd class="text-white">{{ $statusLabel($booking->payment->payment_method) }}</dd></div>
                        @if ($booking->payment->payment_date)
                            <div class="flex justify-between"><dt class="text-gray-400">Paid on</dt><dd class="text-white">{{ $booking->payment->payment_date->format('M d, Y') }}</dd></div>
                        @endif
                    </dl>
                </section>
            @endif
        </div>
    </div>
</div>
