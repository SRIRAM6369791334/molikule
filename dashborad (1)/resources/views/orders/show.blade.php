@extends('layouts.app')

@php
    use App\Models\Order;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-1"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    <i class="bx bx-show me-2 text-primary"></i>
                    Order {{ $order->order_number ?: '#' . $order->id }} Details
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('all-orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">{{ $order->order_number ?: '#' . $order->id }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Status Overview -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-package me-2"></i>Order Status
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="d-flex justify-content-center mb-3">
                        {!! $order->status_badge !!}
                    </div>

                    <!-- Progress bar -->
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar {{ $order->status_progress_class }}" role="progressbar"
                            style="width: {{ $order->status_progress }}%" aria-valuenow="{{ $order->status_progress }}"
                            aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="text-center small text-muted mb-3">
                        {{ $order->status_progress }}% Complete
                    </div>

                    <!-- Status Timeline -->
                    <div class="order-timeline">
                        <div class="timeline-item mb-2">
                            <div class="timeline-marker {{ $order->created_at ? 'bg-success' : 'bg-secondary' }}">
                                <i class="bx bx-time-five"></i>
                            </div>
                            <div class="timeline-content">
                                <small><strong>Created</strong></small><br>
                                <small class="text-muted">{{ $order->created_at->format('d M, Y H:i') }}</small>
                            </div>
                        </div>

                        <div class="timeline-item mb-2">
                            <div class="timeline-marker {{ $order->dispatch_date ? 'bg-info' : 'bg-secondary' }}">
                                <i class="bx bx-send"></i>
                            </div>
                            <div class="timeline-content">
                                <small><strong>Dispatched</strong></small><br>
                                <small class="text-muted">
                                    {{ $order->dispatch_date ? $order->dispatch_date->format('d M, Y H:i') : 'Pending' }}
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker {{ $order->delivery_date ? 'bg-success' : 'bg-secondary' }}">
                                <i class="bx bx-check-double"></i>
                            </div>
                            <div class="timeline-content">
                                <small><strong>Delivered</strong></small><br>
                                <small class="text-muted">
                                    {{ $order->delivery_date ? $order->delivery_date->format('d M, Y H:i') : 'Pending' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-cog me-2"></i>Order Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">

                        @if($order->label_url || $order->invoice_url)
                            <hr class="my-2">
                            <p class="small text-muted mb-2"><i class="bx bx-package me-1"></i>Shiprocket Documents:</p>
                            @if($order->label_url)
                            <a href="{{ $order->label_url }}" class="btn btn-outline-info btn-sm mb-2" target="_blank">
                                <i class="bx bx-barcode-reader me-1"></i>Shipping Label
                            </a>
                            @endif
                            @if($order->invoice_url)
                            <a href="{{ $order->invoice_url }}" class="btn btn-outline-primary btn-sm mb-2" target="_blank">
                                <i class="bx bx-receipt me-1"></i>Courier Invoice
                            </a>
                            @endif
                        @endif

                        <hr class="my-2">
                        <p class="small text-muted mb-2"><i class="bx bx-receipt me-1"></i>Local Invoice Options:</p>

                        @if($order->invoice)
                            <!-- Invoice already exists -->
                            <a href="{{ route('invoice.show', $order->invoice->id) }}" class="btn btn-outline-info btn-sm"
                                target="_blank">
                                <i class="bx bx-show me-1"></i>View Invoice
                            </a>

                            <a href="{{ route('invoice.download', $order->invoice->id) }}"
                                class="btn btn-outline-success btn-sm">
                                <i class="bx bx-download me-1"></i>Download Invoice PDF
                            </a>

                            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-printer me-1"></i>Print Invoice
                            </button>
                        @else
                            <!-- Invoice doesn't exist yet -->
                            <a href="{{ route('orders.invoice.view', $order) }}" class="btn btn-outline-info btn-sm"
                                target="_blank">
                                <i class="bx bx-show me-1"></i>Preview Invoice
                            </a>

                            <a href="{{ route('orders.invoice.download', $order) }}" class="btn btn-outline-success btn-sm">
                                <i class="bx bx-download me-1"></i>Download Invoice PDF
                            </a>

                            <a href="{{ route('orders.invoice.generate', $order) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-receipt me-1"></i>Generate & Save Invoice
                            </a>
                        @endif

                        @if($order->next_status_options)
                            <hr>
                            <p class="small text-muted mb-2">Status Transitions:</p>
                            @foreach($order->next_status_options as $status)
                                <form method="POST" action="{{ route('orders.update-status', $order) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <input type="hidden" name="version" value="{{ $order->version }}">
                                    <button type="submit" class="btn btn-outline-success btn-sm btn-block me-2 mb-2"
                                        onclick="return confirm('Change status to {{ Order::STATUS_LABELS[$status] }}?')">
                                        <i class="bx bx-refresh me-1"></i>{{ Order::STATUS_LABELS[$status] }}
                                    </button>
                                </form>
                            @endforeach
                        @endif
                    </div>

                    @if($order->canDelete())
                        <hr>
                        <div class="text-center">
                            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Delete order {{ $order->order_number ?: '#' . $order->id }}? This cannot be undone.')">
                                    <i class="bx bx-trash me-1"></i>Delete Order
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Performance Metrics -->

        </div>

        <!-- Main Order Details -->
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>Order Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Order Details</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Order #:</td>
                                    <td><strong>{{ $order->order_number ?: '#' . $order->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>{!! $order->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Amount:</td>
                                    <td>
                                        <strong class="text-success fs-5">{{ $order->formatted_total }}</strong>
                                        @if($order->shipping_cost > 0)
                                            <small class="text-muted ms-2">(Incl. ₹{{ number_format($order->shipping_cost, 2) }} Shipping)</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Payment:</td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ strtoupper($order->payment_status ?? 'pending') }}
                                        </span>
                                        <small class="text-muted ms-1">({{ strtoupper($order->payment_method ?? 'cash') }})</small>
                                    </td>
                                </tr>
                                @if($order->razorpay_payment_id)
                                <tr>
                                    <td class="text-muted">Transaction ID:</td>
                                    <td><code class="text-primary">{{ $order->razorpay_payment_id }}</code></td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Order Date:</td>
                                    <td>{{ $order->created_at->format('l, F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td>{{ $order->updated_at->format('l, F j, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Customer Info -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Customer Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Name:</td>
                                    <td>
                                        <strong>{{ $order->customer_name }}</strong>
                                        @if($order->user_id)
                                            <span class="badge bg-success-subtle text-success ms-2 font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary ms-2 font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></td>
                                </tr>
                                @if($order->customer_phone)
                                    <tr>
                                        <td class="text-muted">Phone:</td>
                                        <td><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Addresses -->
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Shipping Address</h6>
                            <div class="bg-light p-3 rounded">
                                <i class="bx bx-map-pin text-primary me-1"></i>
                                <span style="white-space: pre-line;">{{ $order->shipping_address }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Billing Address</h6>
                            <div class="bg-light p-3 rounded">
                                <i class="bx bx-credit-card text-info me-1"></i>
                                <span style="white-space: pre-line;">
                                    {{ $order->billing_address ?: $order->shipping_address }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($order->notes)
                        <hr>
                        <h6 class="text-muted mb-2">Order Notes</h6>
                        <div class="alert alert-light">
                            <i class="bx bx-note text-primary me-2"></i>
                            {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mt-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-shopping-bag me-2"></i>Order Items
                    </h5>
                    <span class="badge bg-primary">{{ $order->orderItems->count() }} items</span>
                </div>
                <div class="card-body">
                    @if($order->orderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-light me-2">
                                                        <div class="avatar-title bg-light text-primary rounded-circle">
                                                            <i class="bx bx-package"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->item_name ?: $item->product_name ?: 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary rounded-pill px-3">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">
                                                ₹{{ number_format($item->unit_price ?: $item->product_price ?: 0, 2) }}</td>
                                            <td class="text-end fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    <!-- Breakdown -->
                                    @php
                                        $subtotal = $order->orderItems->sum('total_price');
                                    @endphp
                                    <tr class="table-light border-top border-2">
                                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end">₹{{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    @if($order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Shipping Charges:</td>
                                        <td class="text-end text-info">+ ₹{{ number_format($order->shipping_cost, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->coupon_discount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Coupon Discount ({{ $order->coupon_code }}):</td>
                                        <td class="text-end text-danger">- ₹{{ number_format($order->coupon_discount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Other Discounts:</td>
                                        <td class="text-end text-danger">- ₹{{ number_format($order->discount_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold fs-5 pt-3">Grand Total:</td>
                                        <td class="text-end fw-bold text-success fs-5 pt-3">{{ $order->formatted_total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="bx bx-shopping-bag display-4 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">No Order Items Found</h5>
                                <p class="text-muted">Order items will be displayed once they are configured.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Track Order (if dispatched) -->
            @if($order->status === Order::STATUS_DISPATCH && $order->tracking_number)
                <div class="card mt-3 border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h5 class="card-title mb-0 text-info">
                            <i class="bx bx-package me-2"></i>Track Your Order
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-2">
                                    <strong>Tracking Number / AWB:</strong>
                                    <code class="bg-light fs-6 px-3 py-1 rounded">{{ $order->awb_code ?: $order->tracking_number }}</code>
                                </p>
                                @if($order->courier_name)
                                <p class="mb-2">
                                    <strong>Courier:</strong>
                                    <span class="badge bg-secondary">{{ $order->courier_name }}</span>
                                </p>
                                @endif
                                <p class="text-muted small mb-0">
                                    Your order was dispatched on {{ $order->dispatch_date->format('l, F j, Y') }} at
                                    {{ $order->dispatch_date->format('H:i') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="progress mb-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 50%"></div>
                                </div>
                                <small class="text-muted">Estimated delivery within 2-3 business days</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delivery Confirmation (if completed) -->
            @if($order->status === Order::STATUS_DELIVERY)
                <div class="card mt-3 border-success">
                    <div class="card-header bg-success bg-opacity-10">
                        <h5 class="card-title mb-0 text-success">
                            <i class="bx bx-check-double me-2"></i>Order Successfully Delivered!
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-2">
                                    <strong>Delivered on:</strong>
                                    {{ $order->delivery_date->format('l, F j, Y') }} at
                                    {{ $order->delivery_date->format('H:i') }}
                                </p>
                                <p class="text-muted small mb-0">
                                    Your order processing took {{ $order->processing_days }} business days from order to
                                    delivery.
                                </p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="avatar-lg rounded-circle bg-success mx-auto mb-2">
                                    <div class="text-center avatar-title bg-success rounded-circle">
                                        <i class="bx bx-check text-white fs-1"></i>
                                    </div>
                                </div>
                                <small class="text-success fw-bold">DELIVERED</small>
                            </div>
                        </div>

                        <!-- Customer Satisfaction Feedback -->
                        <div class="border-top pt-3 mt-3">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h6 class="text-muted mb-2">How was your experience?</h6>
                                    <div class="fs-4 text-warning mb-2">
                                        ⭐⭐⭐⭐⭐
                                    </div>
                                    <p class="text-muted small mb-0">5-star service delivery rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .order-timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 15px;
        }

        .timeline-marker {
            position: absolute;
            left: -45px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: -30px;
            top: 30px;
            width: 2px;
            height: calc(100% - 15px);
            background: #e9ecef;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #dee2e6;
        }
    </style>
@endsection