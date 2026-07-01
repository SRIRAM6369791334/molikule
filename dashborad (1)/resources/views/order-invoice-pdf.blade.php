<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $order->order_number ?: $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 25px;
            border-bottom: 3px solid #4a5568;
            padding-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .header-left {
            width: 50%;
        }

        .header-right {
            width: 50%;
            text-align: right;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .company-details {
            color: #718096;
            font-size: 10px;
            line-height: 1.6;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .invoice-meta {
            font-size: 10px;
            color: #4a5568;
            line-height: 1.8;
        }

        .invoice-meta strong {
            color: #2d3748;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 5px;
        }

        .badge-warning {
            background-color: #feebc8;
            color: #7c2d12;
            border: 1px solid #f6ad55;
        }

        .badge-secondary {
            background-color: #e2e8f0;
            color: #2d3748;
            border: 1px solid #cbd5e0;
        }

        .badge-info {
            background-color: #bee3f8;
            color: #2c5282;
            border: 1px solid #63b3ed;
        }

        .badge-success {
            background-color: #c6f6d5;
            color: #22543d;
            border: 1px solid #68d391;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #cbd5e0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            vertical-align: top;
            padding: 0 10px 0 0;
        }

        .info-column {
            width: 50%;
        }

        .customer-name {
            font-size: 12px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .customer-details {
            font-size: 10px;
            color: #4a5568;
            line-height: 1.6;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table thead th {
            background-color: #f7fafc;
            border: 1px solid #cbd5e0;
            padding: 8px;
            font-size: 10px;
            font-weight: bold;
            color: #2d3748;
            text-align: left;
        }

        .items-table tbody td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            font-size: 10px;
            color: #4a5568;
        }

        .items-table .text-center {
            text-align: center;
        }

        .items-table .text-right {
            text-align: right;
        }

        .item-name {
            font-weight: bold;
            color: #2d3748;
        }

        .item-variant {
            font-size: 9px;
            color: #718096;
            font-style: italic;
        }

        .totals-table {
            width: 300px;
            float: right;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .totals-table td {
            padding: 5px 10px;
            font-size: 10px;
        }

        .totals-table .label {
            text-align: right;
            color: #4a5568;
        }

        .totals-table .value {
            text-align: right;
            color: #2d3748;
            font-weight: bold;
        }

        .totals-table .total-row td {
            border-top: 2px solid #4a5568;
            padding-top: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .totals-table .total-row .label {
            color: #2d3748;
        }

        .totals-table .total-row .value {
            color: #22543d;
        }

        .notes-section {
            clear: both;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .notes-title {
            font-size: 11px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .notes-content {
            font-size: 10px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .payment-status {
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 3px solid #4a5568;
            text-align: center;
            color: #718096;
            font-size: 10px;
            line-height: 1.6;
        }

        .footer-emphasis {
            font-weight: bold;
            color: #2d3748;
        }

        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="company-name">{{ config('app.name', 'Molikule') }}</div>
                        <div class="company-details">
                            {{ config('app.company_address', '123 Business Street, City, State 12345') }}<br>
                            Email: {{ config('app.company_email', 'orders@Molikule.com') }}<br>
                            @if(config('app.company_phone'))
                                Phone: {{ config('app.company_phone') }}<br>
                            @endif
                            @if(config('app.company_gst'))
                                GST: {{ config('app.company_gst') }}
                            @endif
                        </div>
                    </td>
                    <td class="header-right">
                        <div class="invoice-title">TAX INVOICE</div>
                        <div class="invoice-meta">
                            <strong>Invoice #:</strong>
                            {{ $order->order_number ?: 'WGC-ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}<br>
                            <strong>Invoice Date:</strong> {{ $order->created_at->format('d M, Y') }}<br>
                            <strong>Status:</strong>
                            @php
                                $statusClass = match ($order->status) {
                                    'pending' => 'warning',
                                    'processing' => 'secondary',
                                    'dispatch' => 'info',
                                    'delivered' => 'success',
                                    default => 'secondary'
                                };
                                $statusLabel = match ($order->status) {
                                    'pending' => 'Pending',
                                    'processing' => 'Processing',
                                    'dispatch' => 'Dispatched',
                                    'delivered' => 'Delivered',
                                    default => ucfirst($order->status)
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <table class="info-table">
                <tr>
                    <td class="info-column">
                        <div class="section-title">BILL TO</div>
                        <div class="customer-name">{{ $order->customer_name }}</div>
                        <div class="customer-details">
                            @if($order->customer_email)
                                Email: {{ $order->customer_email }}<br>
                            @endif
                            @if($order->customer_phone)
                                Phone: {{ $order->customer_phone }}<br>
                            @endif
                            @if($order->billing_address)
                                {{ $order->billing_address }}
                            @endif
                        </div>
                    </td>
                    <td class="info-column">
                        <div class="section-title">SHIP TO</div>
                        <div class="customer-name">{{ $order->customer_name }}</div>
                        <div class="customer-details">
                            @if($order->shipping_address)
                                {{ $order->shipping_address }}<br>
                            @endif
                            @if($order->pincode)
                                Pincode: {{ $order->pincode }}<br>
                            @endif
                            @if($order->customer_email)
                                Email: {{ $order->customer_email }}<br>
                            @endif
                            @if($order->customer_phone)
                                Phone: {{ $order->customer_phone }}
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Order Items Table -->
        <div class="section avoid-break">
            <div class="section-title">ORDER DETAILS</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">#</th>
                        <th style="width: 45%;">Product Name</th>
                        <th style="width: 12%;" class="text-center">Quantity</th>
                        <th style="width: 15%;" class="text-right">Unit Price</th>
                        <th style="width: 18%;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="item-name">{{ $item->item_name ?: $item->product_name ?: 'N/A' }}</div>
                                @if($item->itemable && $item->itemable instanceof App\Models\ProductVariant)
                                    @php
                                        $attributes = [];
                                        foreach ($item->itemable->attributeValues as $val) {
                                            if ($val->value) {
                                                $attributes[] = ($val->attribute->name ?? 'Option') . ': ' . $val->value;
                                            }
                                        }
                                    @endphp
                                    @if(count($attributes) > 0)
                                        <div class="item-variant">{{ implode(', ', $attributes) }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">₹{{ number_format($item->unit_price ?: $item->product_price ?: 0, 2) }}
                            </td>
                            <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px; color: #718096;">
                                No items found in this order
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <table class="totals-table">
            @php
                $subtotal = $order->orderItems->sum('total_price');
                $tax = 0; 
                $shipping = $order->shipping_cost;
                $discount = ($order->discount_amount ?? 0) + ($order->coupon_discount ?? 0);
            @endphp
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">₹{{ number_format($subtotal, 2) }}</td>
            </tr>
            @if($shipping > 0)
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="value">₹{{ number_format($shipping, 2) }}</td>
                </tr>
            @endif
            @if($tax > 0)
                <tr>
                    <td class="label">Tax:</td>
                    <td class="value">₹{{ number_format($tax, 2) }}</td>
                </tr>
            @endif
            @if($discount > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="value">-₹{{ number_format($discount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="label">TOTAL AMOUNT:</td>
                <td class="value">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>

        <!-- Notes and Payment Status -->
        <div class="notes-section">
            @if($order->notes)
                <div class="notes-title">Order Notes:</div>
                <div class="notes-content">{{ $order->notes }}</div>
            @endif

            <div class="payment-status">
                <span class="notes-title">Payment Method:</span>
                <span class="notes-content">
                    @if(stripos($order->notes ?? '', 'cash on delivery') !== false || stripos($order->notes ?? '', 'COD') !== false)
                        Cash on Delivery (COD)
                    @else
                        Online Payment
                    @endif
                </span>
            </div>

            @if($order->tracking_number)
                <div style="margin-top: 8px;">
                    <span class="notes-title">Tracking Number:</span>
                    <span class="notes-content">{{ $order->tracking_number }}</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-emphasis">Thank you for your business!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any queries, please contact us at {{ config('app.company_email', 'support@Molikule.com') }}</p>
            <p style="margin-top: 8px; font-size: 9px;">
                Powered by {{ config('app.name', 'Molikule') }} &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</body>

</html>