<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->booking_reference }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        
        @media print {
            @page {
                margin: 10mm;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
        .header {
            background: white;
            color: #1a1a1a;
            padding: 15px;
            margin-bottom: 15px;
            border-bottom: 3px solid #2d5016;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }
        .header-right {
            text-align: right;
        }
        .logo-section {
            display: inline-block;
            text-align: left;
        }
        .logo-section img {
            height: 50px;
            width: 50px;
            vertical-align: middle;
            display: inline-block;
            margin-right: 10px;
        }
        .logo-text {
            display: inline-block;
            vertical-align: middle;
            text-align: left;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #1a1a1a;
        }
        .company-tagline {
            font-size: 10px;
            color: #333;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a1a1a;
        }
        .booking-ref {
            font-size: 10px;
            color: #333;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 8px;
        }
        .info-col.right {
            text-align: right;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .customer-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .detail-row {
            margin-bottom: 3px;
            font-size: 10px;
        }
        .vehicle-box {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .vehicle-name {
            font-weight: bold;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        table thead {
            background: #f5f5f5;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            border-bottom: 2px solid #ddd;
        }
        table th.center {
            text-align: center;
        }
        table th.right {
            text-align: right;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        table td.center {
            text-align: center;
        }
        table td.right {
            text-align: right;
        }
        .service-name {
            font-weight: 600;
            margin-bottom: 2px;
            font-size: 10px;
        }
        .service-desc {
            font-size: 9px;
            color: #666;
        }
        .totals {
            float: right;
            width: 250px;
            margin-bottom: 15px;
        }
        .total-row {
            display: table;
            width: 100%;
            padding: 5px 0;
            font-size: 10px;
        }
        .total-row.grand {
            border-top: 2px solid #333;
            padding-top: 8px;
            font-size: 14px;
            font-weight: bold;
        }
        .total-label {
            display: table-cell;
            width: 50%;
        }
        .total-value {
            display: table-cell;
            text-align: right;
            font-weight: 600;
        }
        .total-row.grand .total-value {
            color: #2d5016;
        }
        .payment-box {
            clear: both;
            background: #e8f5e9;
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #4caf50;
            border-radius: 3px;
            font-size: 10px;
        }
        .payment-box.pending {
            background: #fff3cd;
            border-color: #ffc107;
        }
        .payment-header {
            font-size: 9px;
            font-weight: bold;
            color: #2e7d32;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .payment-box.pending .payment-header {
            color: #856404;
        }
        .payment-status {
            font-weight: bold;
            font-size: 11px;
        }
        .notes-box {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 3px;
            font-size: 10px;
        }
        .footer {
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }
        .footer-note {
            margin-bottom: 5px;
            font-size: 10px;
        }
        .footer-timestamp {
            font-size: 9px;
            color: #999;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="invoice-title">INVOICE</div>
                    <div class="booking-ref">Booking: {{ $booking->booking_reference }}</div>
                </div>
                <div class="header-right">
                    <div class="logo-section">
                        <img src="{{ public_path('images/shop.png') }}" alt="Dexter Auto Services">
                        <div class="logo-text">
                            <div class="company-name">DEXTER AUTO SERVICES</div>
                            <div class="company-tagline">Professional Auto Care</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-col">
                    <div class="section-title">Bill To:</div>
                    <div class="customer-name">{{ $booking->customer->name }}</div>
                    <div class="detail-row">{{ $booking->customer->email }}</div>
                    <div class="detail-row">{{ $booking->customer->phone }}</div>
                </div>
                <div class="info-col right">
                    <div class="detail-row">
                        <span style="color: #666;">Invoice Date:</span>
                        <strong>{{ now()->format('M d, Y') }}</strong>
                    </div>
                    <div class="detail-row">
                        <span style="color: #666;">Booking Date:</span>
                        <strong>{{ $booking->booking_date->format('M d, Y') }}</strong>
                    </div>
                    <div class="detail-row">
                        <span style="color: #666;">Status:</span>
                        <strong>{{ strtoupper($booking->status) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Info -->
        <div class="vehicle-box">
            <div class="section-title">Vehicle Details:</div>
            <div class="vehicle-name">
                {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})
            </div>
            <div style="color: #666; font-size: 11px; margin-top: 3px;">
                Registration: {{ $booking->vehicle->registration_number }}
            </div>
        </div>

        <!-- Services Table -->
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th class="center">Quantity</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->services as $service)
                <tr>
                    <td>
                        <div class="service-name">{{ $service->name }}</div>
                        @if($service->description)
                        <div class="service-desc">{{ $service->description }}</div>
                        @endif
                    </td>
                    <td class="center">{{ $service->pivot->quantity }}</td>
                    <td class="right">${{ number_format($service->pivot->price, 2) }}</td>
                    <td class="right" style="font-weight: 600;">
                        ${{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">${{ number_format($booking->total_amount, 2) }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Tax (0%):</div>
                <div class="total-value">$0.00</div>
            </div>
            <div class="total-row grand">
                <div class="total-label">Total:</div>
                <div class="total-value">${{ number_format($booking->total_amount, 2) }}</div>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Notes -->
        @if($booking->notes)
        <div class="notes-box">
            <div class="section-title">Notes:</div>
            <div>{{ $booking->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-note">Thank you for your business!</div>
            <div class="footer-timestamp">
                This invoice was generated on {{ now()->format('M d, Y \a\t h:i A') }}
            </div>
        </div>
    </div>
</body>
</html>
