<div class="space-y-6">
    <!-- Flash Messages -->
    <x-success-modal />

    @if (session()->has('error'))
        <div class="flex items-center gap-3 rounded-2xl bg-rose-500/10 ring-1 ring-rose-500/30 p-4">
            <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium text-rose-200">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Page header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">Track My Car</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Active services</h1>
            <p class="mt-1 text-gray-400">Follow each booking from request to pick-up.</p>
        </div>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/[0.05] ring-1 ring-white/10 px-3.5 py-1.5 text-sm text-gray-300">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            {{ $activeBookings->count() }} active {{ \Illuminate\Support\Str::plural('booking', $activeBookings->count()) }}
        </span>
    </div>

    @forelse($activeBookings as $booking)
        @php
            $steps = [
                ['label' => 'Requested', 'hint' => 'We received your booking', 'done' => in_array($booking->status, ['pending', 'approved', 'completed'])],
                ['label' => 'Approved', 'hint' => 'Your slot is confirmed', 'done' => in_array($booking->status, ['approved', 'completed'])],
                ['label' => 'Completed', 'hint' => 'Ready for pick-up', 'done' => $booking->status === 'completed'],
            ];
            $currentStep = collect($steps)->search(fn ($s) => ! $s['done']);
        @endphp

        <article class="cp-card overflow-hidden">
            <!-- Header -->
            <div class="p-5 sm:p-7 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-white/[0.06]">
                <div class="flex items-center gap-4">
                    <div class="w-16 flex-shrink-0 rounded-xl bg-emerald-400 text-gray-950 text-center py-2">
                        <div class="text-[11px] font-bold uppercase">{{ $booking->booking_date->format('M') }}</div>
                        <div class="text-2xl font-extrabold leading-none">{{ $booking->booking_date->format('d') }}</div>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-bold text-white">Service #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</h2>
                            <x-status-badge :status="$booking->status" />
                        </div>
                        <p class="mt-1 text-gray-400">{{ $booking->booking_date->format('l, F j, Y') }} at {{ $booking->booking_time->format('g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 cp-panel px-4 py-3">
                    <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 17a2 2 0 11-4 0 2 2 0 014 0zM20 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <div>
                        <div class="font-semibold text-white">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                        <div class="text-xs text-gray-400">{{ $booking->vehicle->year }} model</div>
                    </div>
                    <span class="plate-number text-sm ml-2">{{ $booking->vehicle->plate_number }}</span>
                </div>
            </div>

            <div class="p-5 sm:p-7 space-y-6">
                <!-- Progress -->
                <ol class="grid grid-cols-3 gap-2 sm:gap-4" aria-label="Service progress">
                    @foreach($steps as $i => $step)
                        @php $isCurrent = $currentStep === $i; @endphp
                        <li class="relative">
                            <div @class(['h-1.5 rounded-full mb-4', 'bg-emerald-400' => $step['done'], 'bg-gradient-to-r from-emerald-400/60 to-white/10' => $isCurrent, 'bg-white/10' => ! $step['done'] && ! $isCurrent])></div>
                            <div class="flex items-start gap-3">
                                <span @class([
                                    'w-9 h-9 flex-shrink-0 rounded-full flex items-center justify-center text-sm font-bold',
                                    'bg-emerald-400 text-gray-950' => $step['done'],
                                    'bg-emerald-400/15 text-emerald-300 ring-2 ring-emerald-400/50' => $isCurrent,
                                    'bg-white/[0.05] text-gray-500 ring-1 ring-white/10' => ! $step['done'] && ! $isCurrent,
                                ])>
                                    @if($step['done'])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <div @class(['font-semibold', 'text-white' => $step['done'] || $isCurrent, 'text-gray-500' => ! $step['done'] && ! $isCurrent])>{{ $step['label'] }}</div>
                                    <div class="hidden sm:block text-sm text-gray-500">{{ $isCurrent ? 'In progress…' : $step['hint'] }}</div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">
                    <!-- Services -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 mb-3">Services requested</h3>
                        <ul class="cp-panel divide-y divide-white/[0.06]">
                            @foreach($booking->services as $service)
                                <li class="flex items-center justify-between gap-4 px-4 py-3">
                                    <div class="min-w-0">
                                        <div class="font-medium text-white">{{ $service->name }}</div>
                                        @if($service->estimated_duration)
                                            <div class="text-xs text-gray-500">About {{ $service->estimated_duration }} min</div>
                                        @endif
                                    </div>
                                    <div class="font-semibold text-gray-200 tabular-nums">₱{{ number_format($service->pivot->price, 2) }}</div>
                                </li>
                            @endforeach
                            <li class="flex items-center justify-between gap-4 px-4 py-4 bg-emerald-400/[0.05]">
                                <span class="font-semibold text-emerald-200">Estimated total</span>
                                <span class="text-2xl font-bold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</span>
                            </li>
                        </ul>

                        @if($booking->notes)
                            <div class="mt-5">
                                <h3 class="text-sm font-semibold text-gray-300 mb-2">Your notes</h3>
                                <p class="cp-panel p-4 text-gray-300">{{ $booking->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Side -->
                    <div class="space-y-4">
                        @if($booking->mechanic_name)
                            <div class="cp-panel p-4">
                                <h3 class="text-sm font-semibold text-gray-300 mb-3">Your mechanic</h3>
                                <div class="flex items-center gap-3">
                                    <span class="w-11 h-11 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-gray-950 font-bold">
                                        {{ strtoupper(substr($booking->mechanic_name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <div class="font-semibold text-white">{{ $booking->mechanic_name }}</div>
                                        @if($booking->mechanic_phone)
                                            <a href="tel:{{ $booking->mechanic_phone }}" class="text-sm text-emerald-300 hover:text-emerald-200">{{ $booking->mechanic_phone }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($booking->marked_as_late)
                            <div class="rounded-2xl bg-orange-400/[0.08] ring-1 ring-orange-400/30 p-4">
                                <div class="flex items-center gap-2 text-orange-300 font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    You told us you're running late
                                </div>
                                <dl class="mt-3 space-y-1.5 text-sm">
                                    <div class="flex justify-between gap-3"><dt class="text-gray-400">Reported</dt><dd class="text-white">{{ $booking->marked_late_at->format('M d, g:i A') }}</dd></div>
                                    @if($booking->estimated_arrival_time)
                                        <div class="flex justify-between gap-3"><dt class="text-gray-400">Expected arrival</dt><dd class="font-semibold text-orange-200">{{ $booking->estimated_arrival_time->format('g:i A') }}</dd></div>
                                    @endif
                                </dl>
                                @if($booking->late_reason)
                                    <p class="mt-3 pt-3 border-t border-orange-400/20 text-sm text-gray-300">{{ $booking->late_reason }}</p>
                                @endif
                            </div>
                        @endif

                        @if(in_array($booking->status, ['pending', 'approved']))
                            <div class="cp-panel p-4 space-y-3">
                                <h3 class="text-sm font-semibold text-gray-300">Need to change plans?</h3>
                                @if(!$booking->marked_as_late)
                                    <button wire:click="openLateModal({{ $booking->id }})" class="w-full inline-flex items-center justify-center gap-2 h-12 px-5 rounded-xl font-semibold text-orange-200 bg-orange-400/10 ring-1 ring-orange-400/30 hover:bg-orange-400/20 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        I'll be late
                                    </button>
                                @endif
                                <button
                                    wire:click="cancelBooking({{ $booking->id }})"
                                    wire:confirm="Are you sure you want to cancel this service booking?"
                                    class="cp-btn-danger w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Cancel booking
                                </button>
                            </div>
                        @endif

                        <a href="tel:+639270071538" class="flex items-center gap-3 cp-panel p-4 hover:ring-white/15 transition">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>
                                <span class="block text-sm text-gray-400">Questions? Call the shop</span>
                                <span class="block font-semibold text-white">+63 927 007 1538</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="cp-card text-center px-6 py-16">
            <span class="mx-auto w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </span>
            <h2 class="mt-5 text-xl font-bold text-white">No active services</h2>
            <p class="mt-2 text-gray-400">When you book a service, you can follow its progress here.</p>
            <a href="{{ route('customer.book') }}" wire:navigate class="cp-btn-primary mt-6">
                Book a service
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    @endforelse

    <!-- Mark as Late Modal -->
    @if($showLateModal)
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closeLateModal">
            <div class="w-full max-w-md rounded-3xl bg-[#111614] ring-1 ring-white/10 shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between p-6 pb-0">
                    <div class="flex items-center gap-3">
                        <span class="w-11 h-11 rounded-xl bg-orange-400/10 text-orange-300 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-white">Running late?</h3>
                            <p class="text-sm text-gray-400">We'll keep your slot ready.</p>
                        </div>
                    </div>
                    <button wire:click="closeLateModal" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5" aria-label="Close">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="markAsLate" class="p-6 space-y-5">
                    <div>
                        <label for="estimatedArrivalTime" class="cp-label">When will you arrive? <span class="text-rose-400">*</span></label>
                        <input type="time" id="estimatedArrivalTime" wire:model="estimatedArrivalTime" required class="cp-input">
                        @error('estimatedArrivalTime') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="lateReason" class="cp-label">Reason <span class="text-gray-500">(optional)</span></label>
                        <textarea id="lateReason" wire:model="lateReason" rows="3" maxlength="500" placeholder="e.g. Heavy traffic" class="cp-input resize-none"></textarea>
                        <div class="mt-1 text-right text-xs text-gray-500">{{ strlen($lateReason ?? '') }}/500</div>
                        @error('lateReason') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" wire:click="closeLateModal" class="cp-btn-ghost">Never mind</button>
                        <button type="submit" class="inline-flex items-center justify-center h-12 px-5 rounded-xl font-semibold text-gray-950 bg-orange-400 hover:bg-orange-300 transition">Notify the shop</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
