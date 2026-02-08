<div>
    <style>
        .invoice-logo {
            filter: brightness(0) invert(1);
        }
        
        @media print {
            /* Hide everything except invoice */
            body * {
                visibility: hidden;
            }
            
            /* Hide header, navigation, footer */
            header, nav, footer, .navbar, .sidebar, .menu, [role="navigation"] {
                display: none !important;
            }
            
            /* Show only invoice content */
            #invoice-content, #invoice-content * {
                visibility: visible;
            }
            
            #invoice-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            
            /* Fit to one page */
            @page {
                size: A4;
                margin: 10mm;
            }
            
            /* Remove browser header/footer */
            @page {
                margin-top: 0;
                margin-bottom: 0;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            /* Hide browser default header/footer */
            title {
                display: none;
            }
            
            /* Reduce sizes to fit one page */
            .max-w-4xl {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            
            .p-8 {
                padding: 15px !important;
            }
            
            .p-6 {
                padding: 10px !important;
            }
            
            .p-4 {
                padding: 8px !important;
            }
            
            .mb-8 {
                margin-bottom: 10px !important;
            }
            
            .py-8 {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
            
            /* Reset header for print */
            .print-header {
                background: white !important;
                border-bottom: 3px solid #2d5016 !important;
            }
            
            .print-text {
                color: #1a1a1a !important;
            }
            
            .invoice-logo {
                filter: none !important;
            }
            
            /* Reduce table spacing */
            table {
                font-size: 11px;
            }
            
            table td, table th {
                padding: 6px !important;
            }
        }
    </style>
    
    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Invoice Container -->
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden" id="invoice-content">
        <!-- Invoice Header -->
        <div class="bg-gradient-to-r from-garage-charcoal to-garage-darkgreen text-white p-8 print-header">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold mb-2 print-text">INVOICE</h1>
                    <p class="text-garage-offwhite print-text">Booking: {{ $booking->booking_reference }}</p>
                </div>
                <div class="text-right">
                    <div class="flex items-center justify-end gap-3 mb-2">
                        <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-16 w-16 object-contain invoice-logo">
                        <div>
                            <h2 class="text-2xl font-bold print-text">DEXTER AUTO SERVICES</h2>
                            <p class="text-sm text-garage-offwhite mt-1 print-text">Professional Auto Care</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="p-8">
            <div class="grid grid-cols-2 gap-8 mb-8">
                <!-- Bill To -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Bill To:</h3>
                    <div class="text-gray-800">
                        <p class="font-bold text-lg">{{ $booking->customer->name }}</p>
                        <p>{{ $booking->customer->email }}</p>
                        <p>{{ $booking->customer->phone }}</p>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="text-right">
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-600">Invoice Date:</span>
                            <span class="font-semibold ml-2">{{ now()->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Booking Date:</span>
                            <span class="font-semibold ml-2">{{ $booking->booking_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="ml-2 px-3 py-1 rounded-full text-xs font-bold 
                                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($booking->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-8">
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Vehicle Details:</h3>
                <p class="text-gray-800">
                    <span class="font-bold">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</span>
                    <span class="text-gray-600 ml-2">({{ $booking->vehicle->year }})</span>
                </p>
                <p class="text-gray-600 text-sm">Registration: {{ $booking->vehicle->registration_number }}</p>
            </div>

            <!-- Services Table -->
            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-300">
                            <th class="text-left py-3 text-sm font-semibold text-gray-600 uppercase">Service</th>
                            <th class="text-center py-3 text-sm font-semibold text-gray-600 uppercase">Quantity</th>
                            <th class="text-right py-3 text-sm font-semibold text-gray-600 uppercase">Unit Price</th>
                            <th class="text-right py-3 text-sm font-semibold text-gray-600 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->services as $service)
                        <tr class="border-b border-gray-200">
                            <td class="py-4 text-gray-800">
                                <div class="font-medium">{{ $service->name }}</div>
                                @if($service->description)
                                <div class="text-sm text-gray-600 mt-1">{{ $service->description }}</div>
                                @endif
                            </td>
                            <td class="py-4 text-center text-gray-800">{{ $service->pivot->quantity }}</td>
                            <td class="py-4 text-right text-white">${{ number_format($service->pivot->price, 2) }}</td>
                            <td class="py-4 text-right font-bold text-white">
                                ${{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-8">
                <div class="w-64">
                    <div class="flex justify-between py-2 border-t border-gray-300">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold text-gray-800">${{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Tax (0%):</span>
                        <span class="font-semibold text-gray-800">$0.00</span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-gray-300">
                        <span class="text-lg font-bold text-gray-800">Total:</span>
                        <span class="text-2xl font-bold text-garage-darkgreen">
                            ${{ number_format($booking->total_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($booking->notes)
            <div class="bg-gray-50 rounded-lg p-4 mb-8">
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Notes:</h3>
                <p class="text-gray-700">{{ $booking->notes }}</p>
            </div>
            @endif

            <!-- Footer -->
            <div class="text-center pt-8 border-t border-gray-300">
                <p class="text-gray-600 text-sm">Thank you for your business!</p>
                <p class="text-gray-500 text-xs mt-2">
                    This invoice was generated on {{ now()->format('M d, Y \a\t h:i A') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-4 justify-center print:hidden">
        <button wire:click="downloadPdf" 
            class="flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-bold transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            DOWNLOAD PDF
        </button>
        
        <button onclick="window.print()" 
            class="flex items-center gap-2 bg-garage-darkgreen text-white px-6 py-3 rounded-lg hover:bg-garage-forest font-bold transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            PRINT INVOICE
        </button>
        
        <a href="{{ route('staff.booking.detail', $booking->id) }}" 
            class="flex items-center gap-2 bg-garage-charcoal text-white px-6 py-3 rounded-lg hover:bg-gray-700 font-bold transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            BACK TO BOOKING
        </a>
    </div>
    </div>

    <script>
        // Change page title for printing
        document.addEventListener('DOMContentLoaded', function() {
            const originalTitle = document.title;
            
            window.addEventListener('beforeprint', function() {
                document.title = 'Invoice {{ $booking->booking_reference }}';
            });
            
            window.addEventListener('afterprint', function() {
                document.title = originalTitle;
            });
        });
    </script>
</div>
