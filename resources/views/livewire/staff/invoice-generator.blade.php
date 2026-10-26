<div class="space-y-6">
    <style>
        @media print {
            body * { visibility: hidden; }
            #invoice-content, #invoice-content * { visibility: visible; }
            #invoice-content { position: absolute; inset: 0 auto auto 0; width: 100%; box-shadow: none !important; border-radius: 0 !important; }
            @page { size: A4; margin: 12mm; }
        }
    </style>

    <!-- Toolbar -->
    <div class="flex flex-wrap items-end justify-between gap-4 print:hidden">
        <div>
            <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-gray-400 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
            </a>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">Invoice</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            <button onclick="window.print()" class="cp-btn-ghost">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <button wire:click="downloadPdf" wire:loading.attr="disabled" wire:target="downloadPdf" class="cp-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span wire:loading.remove wire:target="downloadPdf">Download PDF</span>
                <span wire:loading wire:target="downloadPdf">Preparing...</span>
            </button>
        </div>
    </div>

    <!-- Invoice paper -->
    <article id="invoice-content" class="mx-auto max-w-3xl rounded-3xl bg-white text-gray-900 shadow-2xl shadow-black/50 overflow-hidden">
        <div class="h-2 bg-gradient-to-r from-emerald-500 to-emerald-700"></div>
        <div class="p-8 sm:p-10">
            <!-- Letterhead -->
            <header class="flex flex-wrap items-start justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="w-16 h-16 object-contain">
                    <div>
                        <p class="text-xl font-extrabold tracking-tight">Dexter Auto Services</p>
                        <p class="text-sm text-gray-500">Don Luis Village, Adelfa St, City of Mati</p>
                        <p class="text-sm text-gray-500">+63 927 007 1538</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-extrabold tracking-tight text-emerald-700">INVOICE</p>
                    <p class="mt-1 text-sm text-gray-500">No. <span class="font-mono font-semibold text-gray-800">{{ $booking->booking_reference ?? str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                    <p class="text-sm text-gray-500">Issued {{ now()->format('M d, Y') }}</p>
                </div>
            </header>

            <!-- Parties -->
            <section class="mt-8 grid gap-6 sm:grid-cols-3 rounded-2xl bg-gray-50 p-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Bill to</p>
                    <p class="mt-1 font-semibold">{{ $booking->customer->getDisplayName() }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->customer->getContactPhone() }}</p>
                    <p class="text-sm text-gray-600 break-all">{{ $booking->customer->getContactEmail() }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Vehicle</p>
                    <p class="mt-1 font-semibold">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->vehicle?->year }} · Plate {{ $booking->vehicle?->plate_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Service date</p>
                    <p class="mt-1 font-semibold">{{ $booking->booking_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->booking_time->format('g:i A') }}@if ($booking->mechanic_name) · {{ $booking->mechanic_name }}@endif</p>
                </div>
            </section>

            <!-- Lines -->
            <table class="mt-8 w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200 text-left text-xs uppercase tracking-wider text-gray-500">
                        <th class="py-3 font-semibold">Service</th>
                        <th class="py-3 font-semibold text-center w-16">Qty</th>
                        <th class="py-3 font-semibold text-right w-32">Unit price</th>
                        <th class="py-3 font-semibold text-right w-32">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking->services as $service)
                        <tr class="border-b border-gray-100">
                            <td class="py-3.5 pr-4">
                                <p class="font-medium">{{ $service->name }}</p>
                                @if ($service->description)
                                    <p class="text-xs text-gray-500">{{ $service->description }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 text-center tabular-nums">{{ $service->pivot->quantity }}</td>
                            <td class="py-3.5 text-right tabular-nums">₱{{ number_format($service->pivot->price, 2) }}</td>
                            <td class="py-3.5 text-right font-semibold tabular-nums">₱{{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="mt-6 flex justify-end">
                <dl class="w-full max-w-xs space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd class="tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tax (0%)</dt><dd class="tabular-nums">₱0.00</dd></div>
                    <div class="flex justify-between items-baseline border-t-2 border-gray-200 pt-3">
                        <dt class="font-bold">Total due</dt>
                        <dd class="text-2xl font-extrabold text-emerald-700 tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</dd>
                    </div>
                    @if ($booking->payment)
                        <div class="flex justify-between text-xs text-gray-500">
                            <dt>Payment ({{ ucwords(str_replace('_', ' ', $booking->payment->payment_method)) }})</dt>
                            <dd class="font-semibold {{ $booking->payment->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-600' }}">{{ ucfirst($booking->payment->payment_status) }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if ($booking->notes)
                <section class="mt-8 rounded-2xl bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Notes</p>
                    <p class="mt-1 text-sm text-gray-700">{{ $booking->notes }}</p>
                </section>
            @endif

            <footer class="mt-10 pt-6 border-t border-gray-200 text-center">
                <p class="font-semibold text-gray-700">Thank you for trusting Dexter Auto Services!</p>
                <p class="mt-1 text-xs text-gray-400">Generated {{ now()->format('M d, Y \a\t g:i A') }}</p>
            </footer>
        </div>
    </article>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const originalTitle = document.title;
            window.addEventListener('beforeprint', () => document.title = 'Invoice {{ $booking->booking_reference }}');
            window.addEventListener('afterprint', () => document.title = originalTitle);
        });
    </script>
</div>
