@php
    $isOpen = ! in_array($booking->status, ['completed', 'cancelled', 'no_show', 'not_available']);
    $statusLabel = fn ($s) => ucwords(str_replace('_', ' ', (string) $s));
@endphp

<div class="space-y-6">
    <x-success-modal />

    <!-- Header -->
    <div>
        <a href="{{ route('owner.bookings') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-gray-400 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            All bookings
        </a>
        <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-bold tracking-tight text-white">Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</h1>
                    <x-status-badge :status="$booking->status" class="!text-sm !px-3" />
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

    @if ($booking->marked_as_late)
        <div class="flex flex-wrap items-center gap-3 rounded-2xl bg-orange-400/10 ring-1 ring-orange-400/30 p-4 text-orange-100">
            <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><span class="font-semibold">Customer is running late</span>
                @if ($booking->estimated_arrival_time) · expected at {{ $booking->estimated_arrival_time->format('g:i A') }} @endif
                @if ($booking->late_reason) · "{{ $booking->late_reason }}" @endif
            </span>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem] items-start">
        <!-- Main column -->
        <div class="space-y-6 min-w-0">
            <!-- Customer & vehicle -->
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
                        <div class="flex gap-3"><dt class="w-16 text-gray-500">Phone</dt><dd><a href="tel:{{ $booking->customer->getContactPhone() }}" class="text-emerald-300 hover:text-emerald-200">{{ $booking->customer->getContactPhone() }}</a></dd></div>
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

            <!-- Services -->
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
                            <div class="text-right flex-shrink-0">
                                <p class="font-semibold text-white tabular-nums">₱{{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}</p>
                                @if ($service->pivot->quantity > 1)
                                    <p class="text-xs text-gray-500">{{ $service->pivot->quantity }} × ₱{{ number_format($service->pivot->price, 2) }}</p>
                                @endif
                            </div>
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

            <!-- Timeline -->
            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-lg font-bold text-white">Timeline</h2>
                <ol class="mt-4 relative border-l border-white/10 ml-2 space-y-5">
                    @foreach ($booking->statusLogs->sortByDesc('created_at') as $log)
                        <li class="ml-5">
                            <span class="absolute -left-[5px] mt-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
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
                        <p class="mt-1 text-xs text-gray-500">{{ $booking->created_at->format('M d, Y g:i A') }} · {{ $booking->created_at->diffForHumans() }}</p>
                    </li>
                </ol>
            </section>
        </div>

        <!-- Side column -->
        <div class="space-y-6 lg:sticky lg:top-6">
            <!-- Next step -->
            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-lg font-bold text-white">Next step</h2>
                @if ($isOpen)
                    <p class="text-sm text-gray-400">
                        {{ $booking->status === 'pending' ? 'Confirm the slot so the customer knows you\'re expecting them.' : 'Once the work is done, mark it completed to create the invoice.' }}
                    </p>
                    <div class="mt-4 space-y-2.5">
                        @if ($booking->status === 'pending')
                            <button wire:click="updateStatus('approved')" wire:loading.attr="disabled" class="cp-btn-primary w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Approve booking
                            </button>
                        @endif
                        @if ($booking->status === 'approved')
                            <button wire:click="updateStatus('completed')" wire:loading.attr="disabled" class="cp-btn-primary w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Mark as completed
                            </button>
                            <button wire:click="updateStatus('no_show')" wire:confirm="Mark this customer as not arriving?" wire:loading.attr="disabled"
                                    class="w-full inline-flex items-center justify-center gap-2 h-12 px-5 rounded-xl font-semibold text-orange-200 bg-orange-400/10 ring-1 ring-orange-400/30 hover:bg-orange-400/20 transition">
                                Customer didn't arrive
                            </button>
                        @endif
                        <button wire:click="updateStatus('not_available')" wire:confirm="Mark this booking as not available? The customer will see it was declined." wire:loading.attr="disabled" class="cp-btn-danger w-full">
                            Decline (not available)
                        </button>
                    </div>
                    <div class="mt-4">
                        <label for="statusNote" class="cp-label">Note for this change <span class="text-gray-500">(optional)</span></label>
                        <textarea id="statusNote" wire:model="notes" rows="3" class="cp-input" placeholder="e.g. Parts arrive Thursday"></textarea>
                    </div>
                @else
                    <p class="mt-1 text-sm text-gray-400">This booking is closed ({{ $statusLabel($booking->status) }}). No further action needed.</p>
                @endif
            </section>

            <!-- Mechanic -->
            <x-mechanic-form :booking="$booking" />

            <!-- Payment -->
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

            <!-- Invoice -->
            @if ($booking->status === 'completed')
                <section class="cp-card p-5 sm:p-6">
                    <h2 class="text-lg font-bold text-white">Invoice</h2>
                    <p class="text-sm text-gray-400">View, print or download the invoice for this job.</p>
                    <a href="{{ route('owner.invoice', $booking->id) }}" wire:navigate class="cp-btn-ghost w-full mt-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Open invoice
                    </a>
                </section>
            @endif
        </div>
    </div>
</div>
