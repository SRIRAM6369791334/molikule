@extends('layouts.invoice')

@section('title', 'Invoice #' . ($order->order_number ?: $order->id))

@section('content')
    <div class="card border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center no-print">
            <h4 class="card-title mb-0">Order Invoice #{{ $order->order_number ?: $order->id }}</h4>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bx bx-printer me-1"></i>Print Invoice
                </button>
                <a href="{{ route('orders.invoice.download', $order) }}" class="btn btn-success">
                    <i class="bx bx-download me-1"></i>Download PDF
                </a>
                {{-- <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i>Back to Order
                </a> --}}
            </div>
        </div>
        <div class="card-body p-4">
            <div id="invoice-template">
                <!-- Company Header -->
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-6">
                        <div class="invoice-logo mb-3">
                            <img src="{{ asset('assets/images/logo-2.png') }}" alt="Molikule Logo" style="max-height: 70px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.05));">
                        </div>
                        <h4 class="mb-2 fw-bold" style="color: #0f172a; font-family: 'Outfit', sans-serif;">{{ config('app.company_name', 'Molikule Green Care') }}</h4>
                        <p class="text-muted mb-1 small" style="line-height: 1.6;">
                            Plot. No. 4, SIDCO Industrial Estate, Selliampatti ( PO ), Dharmapuri – 636 809, Tamilnadu.</p>
                        <p class="text-muted mb-1 small"><strong>Email:</strong> mgc@molikule.com / support@molikule.com</p>
                        <p class="text-muted small"><strong>Phone:</strong> {{ config('app.company_phone', '+91 9715699666') }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <h2 class="mb-3 fw-bold text-primary">TAX INVOICE</h2>
                        <div class="mb-2">
                            <strong>Invoice #:</strong>
                            {{ $order->order_number ?: 'WGC-ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="mb-2">
                            <strong>Invoice Date:</strong> {{ $order->created_at->format('d M, Y') }}
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            {!! $order->status_badge !!}
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-bold border-bottom pb-2">Bill To:</h5>
                        <h6 class="mb-1 fw-bold">{{ $order->customer_name }}</h6>
                        @if($order->customer_email)
                            <p class="text-muted mb-1 small">Email: {{ $order->customer_email }}</p>
                        @endif
                        @if($order->customer_phone)
                            <p class="text-muted mb-1 small">Phone: {{ $order->customer_phone }}</p>
                        @endif
                        @if($order->billing_address)
                            <p class="text-muted small">{{ $order->billing_address }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-bold border-bottom pb-2">Ship To:</h5>
                        <h6 class="mb-1 fw-bold">{{ $order->customer_name }}</h6>
                        @if($order->shipping_address)
                            <p class="text-muted mb-1 small">{{ $order->shipping_address }}</p>
                        @endif
                        @if($order->pincode)
                            <p class="text-muted mb-1 small">Pincode: {{ $order->pincode }}</p>
                        @endif
                        @if($order->customer_email)
                            <p class="text-muted mb-1 small">Email: {{ $order->customer_email }}</p>
                        @endif
                        @if($order->customer_phone)
                            <p class="text-muted small">Phone: {{ $order->customer_phone }}</p>
                        @endif
                    </div>
                </div>

                <!-- Order Items Table -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3 fw-bold border-bottom pb-2">Order Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">Product Name</th>
                                        <th class="text-center" style="width: 15%;">Quantity</th>
                                        <th class="text-end" style="width: 17%;">Unit Price</th>
                                        <th class="text-end" style="width: 18%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->orderItems as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->item_name ?: $item->product_name ?: 'N/A' }}</strong>
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
                                                        <br><small class="text-muted">{{ implode(', ', $attributes) }}</small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">
                                                ₹{{ number_format($item->unit_price ?: $item->product_price ?: 0, 2) }}</td>
                                            <td class="text-end fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No items found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Totals Section -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @php
                                        $subtotal = $order->orderItems->sum('total_price');
                                    @endphp
                                    <tr>
                                        <td class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end">₹{{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    @if($order->shipping_cost > 0)
                                    <tr>
                                        <td class="text-end fw-bold">Shipping Charges:</td>
                                        <td class="text-end">₹{{ number_format($order->shipping_cost, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->coupon_discount > 0)
                                    <tr>
                                        <td class="text-end fw-bold">Discount ({{ $order->coupon_code }}):</td>
                                        <td class="text-end">- ₹{{ number_format($order->coupon_discount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td class="text-end fw-bold">Other Discounts:</td>
                                        <td class="text-end">- ₹{{ number_format($order->discount_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td class="text-end fw-bold fs-5 text-dark pt-3">
                                            TOTAL AMOUNT:
                                        </td>
                                        <td class="text-end fw-bold text-success fs-4 pt-3">
                                            ₹{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment & Notes -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12">
                        @if($order->notes)
                            <div class="mb-3">
                                <h6 class="fw-bold text-dark">Order Notes:</h6>
                                <p class="text-muted small">{{ $order->notes }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <h6 class="fw-bold text-dark">Payment Method:</h6>
                            <p class="text-muted small">
                                @if(stripos($order->notes ?? '', 'cash on delivery') !== false || stripos($order->notes ?? '', 'COD') !== false)
                                    Cash on Delivery (COD)
                                @else
                                    Online Payment
                                @endif
                            </p>
                        </div>

                        @if($order->tracking_number)
                            <div class="mb-3">
                                <h6 class="fw-bold text-dark">Tracking Number:</h6>
                                <p class="text-muted small">{{ $order->tracking_number }}</p>
                            </div>
                        @endif

                        <hr class="my-4">
                        <div class="text-center text-muted">
                            <p class="mb-1 fw-bold text-dark">Thank you for your business!</p>
                            <small>This is a computer-generated invoice and does not require a signature.</small><br>
                            <small>For any queries, please contact us at mgc@molikule.com or support@molikule.com</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Outfit', sans-serif !important;
            color: #0f172a;
        }

        .text-primary {
            color: #bbd700 !important;
        }

        .bg-primary {
            background-color: #bbd700 !important;
        }

        .btn-primary {
            background-color: #bbd700 !important;
            border-color: #bbd700 !important;
            font-weight: 700;
        }

        .table-light {
            background-color: #f8fafc !important;
        }

        .fw-bold {
            font-weight: 800 !important;
        }

        @media print {
            body {
                background: white !important;
                font-family: 'Outfit', sans-serif !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header {
                display: none !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .invoice-logo img {
                max-height: 60px;
            }

            table {
                font-size: 11px;
            }

            .fs-4 {
                font-size: 18px !important;
            }

            .fs-5 {
                font-size: 16px !important;
            }

            .text-success {
                color: #22c55e !important;
            }

            .border-bottom {
                border-bottom: 1px solid #e2e8f0 !important;
            }
        }

        .invoice-logo img {
            max-height: 70px;
        }
    </style>
@endpush