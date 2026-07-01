@extends('layouts.app')

@php
    use App\Models\Order;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    <i class="bx bx-calendar-day me-2 text-primary"></i>Today's Orders
                    <span class="badge bg-primary ms-2">{{ $orders->count() }}</span>
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('all-orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Today</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="card-title mb-0">Orders from {{ today()->format('l, F j, Y') }}</h4>
                            <p class="card-title-desc mb-0">
                                <small class="text-muted">
                                    Total Revenue: <strong
                                        class="text-success">₹{{ number_format($orders->sum('total_amount'), 2) }}</strong>
                                    |
                                    Revenue: ₹{{ number_format($stats['today_orders_revenue'], 2) }} |
                                    All Statuses Shown
                                </small>
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-1"></i>All Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Order Status Summary -->
                    <div class="row mb-4">
                        @php
                            $statusCounts = $orders->groupBy('status')->map->count();
                            $totalToday = $orders->count();
                        @endphp

                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center p-3">
                                    <i class="bx bx-time-five text-warning display-5"></i>
                                    <h5 class="mt-2">{{ $statusCounts->get('pending', 0) }}</h5>
                                    <p class="text-muted small mb-0">Pending</p>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ $totalToday > 0 ? ($statusCounts->get('pending', 0) / $totalToday) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center p-3">
                                    <i class="bx bx-send text-info display-5"></i>
                                    <h5 class="mt-2">{{ $statusCounts->get('dispatch', 0) }}</h5>
                                    <p class="text-muted small mb-0">Dispatched</p>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-info"
                                            style="width: {{ $totalToday > 0 ? ($statusCounts->get('dispatch', 0) / $totalToday) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center p-3">
                                    <i class="bx bx-check-double text-success display-5"></i>
                                    <h5 class="mt-2">{{ $statusCounts->get('delivered', 0) }}</h5>
                                    <p class="text-muted small mb-0">Delivered</p>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $totalToday > 0 ? ($statusCounts->get('delivered', 0) / $totalToday) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-secondary">
                                <div class="card-body text-center p-3">
                                    <i class="bx bx-trending-up text-primary display-5"></i>
                                    <h5 class="mt-2 text-primary">₹{{ number_format($stats['today_orders_revenue'], 2) }}
                                    </h5>
                                    <p class="text-muted small mb-0">Total Revenue</p>
                                    <div class="small text-muted mt-1">
                                        {{ $totalToday }} orders today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Order #</th>
                                    <th class="align-middle">Customer</th>
                                    <th class="align-middle">Time</th>
                                    <th class="align-middle">Amount</th>
                                    <th class="align-middle">Status</th>
                                    <th class="align-middle">Progress</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr data-order-id="{{ $order->id }}"
                                        class="table-{{ ['pending' => 'warning', 'dispatch' => 'info', 'delivered' => 'success'][$order->status] ?? 'light' }} bg-light bg-opacity-25">
                                        <td>
                                            <a href="javascript: void(0);" class="text-body fw-bold order-link"
                                                data-bs-toggle="modal" data-bs-target="#orderDetailsModal"
                                                data-order-id="{{ $order->id }}">
                                                {{ $order->order_number ?: '#' . $order->id }}
                                            </a>
                                            <br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">
                                                    {{ $order->customer_name }}
                                                    <div class="mt-1">
                                                        @if($order->user_id)
                                                            <span class="badge badge-pill bg-success-subtle text-success font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>
                                                        @else
                                                            <span class="badge badge-pill bg-secondary-subtle text-secondary font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>
                                                        @endif
                                                    </div>
                                                </h6>
                                                <small class="text-muted">{{ $order->customer_email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $order->created_at->format('H:i') }}</strong>
                                            <br><small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td class="text-success font-weight-600">
                                            {{ $order->formatted_total }}
                                        </td>
                                        <td>
                                            {!! $order->status_badge !!}

                                            <!-- Show time elapsed for dispatched orders -->
                                            @if($order->status === Order::STATUS_DISPATCH && $order->dispatch_date)
                                                <br><small class="text-muted mt-1 d-block">
                                                    Dispatched {{ $order->dispatch_date->diffForHumans() }}
                                                </small>
                                            @endif

                                            <!-- Show delivery time for completed orders -->
                                            @if($order->status === Order::STATUS_DELIVERY && $order->delivery_date)
                                                <br><small class="text-muted mt-1 d-block">
                                                    Completed {{ $order->delivery_date->diffForHumans() }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar {{ $order->status_progress_class }}" role="progressbar"
                                                    style="width: {{ $order->status_progress }}%"
                                                    aria-valuenow="{{ $order->status_progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $order->status_progress }}% Complete</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark p-0 dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded font-size-18"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('orders.show', $order) }}">
                                                            <i class="bx bx-show me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('orders.invoice.print', $order) }}" target="_blank">
                                                            <i class="bx bx-printer me-2"></i>Print Invoice
                                                        </a>
                                                    </li>
                                                    @if(count($order->next_status_options) > 0)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @foreach($order->next_status_options as $status)
                                                            <li>
                                                                <form method="POST" action="{{ route('orders.update-status', $order) }}"
                                                                    style="display: inline;">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Change status to {{ Order::STATUS_LABELS[$status] }}?')">
                                                                        <i
                                                                            class="bx bx-{{ $status === Order::STATUS_DISPATCH ? 'send' : 'check-double' }} me-2 text-{{ $status === Order::STATUS_DELIVERY ? 'success' : 'info' }}"></i>{{ Order::STATUS_LABELS[$status] }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-calendar-x display-4 text-muted mb-3"></i>
                                                <h5 class="text-muted mb-2">No Orders Today</h5>
                                                <p class="text-muted mb-4">
                                                    No orders were placed today. Orders will appear here as they are received.
                                                </p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('all-orders.index') }}" class="btn btn-outline-primary">
                                                        <i class="bx bx-list me-1"></i>View All Orders
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Statistics -->
                    @if($orders->count() > 0)
                        <div class="row mt-4 pt-4 border-top">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="h4 mb-3 text-center">Today's Performance Summary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle bg-warning flex-shrink-0 me-3">
                                                            <div class="text-center avatar-title bg-warning rounded-circle">
                                                                <i class="bx bx-time-five text-white font-size-18"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-warning">Pending Orders</h6>
                                                            <p class="text-muted mb-0 small">Awaiting attention</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="text-warning mb-0">{{ $statusCounts->get('pending', 0) }}</h3>
                                                    <small
                                                        class="text-muted">{{ $totalToday > 0 ? round(($statusCounts->get('pending', 0) / $totalToday) * 100, 1) : 0 }}%
                                                        of total</small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle bg-info flex-shrink-0 me-3">
                                                            <div class="text-center avatar-title bg-info rounded-circle">
                                                                <i class="bx bx-send text-white font-size-18"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-info">Dispatched Orders</h6>
                                                            <p class="text-muted mb-0 small">In transit</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="text-info mb-0">{{ $statusCounts->get('dispatch', 0) }}</h3>
                                                    <small
                                                        class="text-muted">{{ $totalToday > 0 ? round(($statusCounts->get('dispatch', 0) / $totalToday) * 100, 1) : 0 }}%
                                                        of total</small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle bg-success flex-shrink-0 me-3">
                                                            <div class="text-center avatar-title bg-success rounded-circle">
                                                                <i class="bx bx-check-double text-white font-size-18"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-success">Delivered Orders</h6>
                                                            <p class="text-muted mb-0 small">Completed successfully</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="text-success mb-0">{{ $statusCounts->get('delivered', 0) }}</h3>
                                                    <small
                                                        class="text-muted">{{ $totalToday > 0 ? round(($statusCounts->get('delivered', 0) / $totalToday) * 100, 1) : 0 }}%
                                                        of total</small>
                                                </td>
                                            </tr>

                                            <tr class="table-light border-top">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle bg-primary flex-shrink-0 me-3">
                                                            <div class="text-center avatar-title bg-primary rounded-circle">
                                                                <i class="bx bx-trending-up text-white font-size-18"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-primary">Total Revenue</h6>
                                                            <p class="text-muted mb-0 small">Today's earnings</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h3 class="text-primary mb-0">
                                                        ₹{{ number_format($stats['today_orders_revenue'], 2) }}</h3>
                                                    <small class="text-muted">{{ $totalToday }} orders total</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Today's Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <!-- Order details will be loaded here via AJAX -->
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printOrderDetails">Print</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Enhanced order details modal loading
            $('#orderDetailsModal').on('show.bs.modal', function (event) {
                const $modal = $(this);
                const $button = $(event.relatedTarget);
                const orderId = $button.data('order-id');

                $modal.find('.modal-title').text(`Today's Order ${$button.text().trim()} Details`);

                fetch(`{{ url('/orders') }}/${orderId}/details`)
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 401 || response.status === 419) {
                                throw new Error('Session expired');
                            }
                            throw new Error(`HTTP error ${response.status}`);
                        }
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            throw new Error('Invalid response from server');
                        }
                    })
                    .then(data => {
                        const order = data.order;
                        let html = `
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="fw-bold">📋 Order Information</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <p><strong>Order Number:</strong> ${order.order_number || '#' + order.id}</p>
                                    <p><strong>Status:</strong> ${order.status_badge}</p>
                                    <p><strong>Total Amount:</strong> <span class="text-success fw-bold">${order.formatted_total}</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Created Today at:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                    ${order.dispatch_date ? `<p><strong>Dispatched:</strong> ${new Date(order.dispatch_date).toLocaleString()}</p>` : ''}
                                    ${order.delivery_date ? `<p><strong>Delivered:</strong> ${new Date(order.delivery_date).toLocaleString()}</p>` : ''}
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">👤 Customer Information</h6>
                                    <p><strong>Name:</strong> ${order.customer_name} ${order.user_id ? '<span class="badge bg-success-subtle text-success ms-1 font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>' : '<span class="badge bg-secondary-subtle text-secondary ms-1 font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>'}</p>
                                    <p><strong>Email:</strong> <a href="mailto:${order.customer_email}">${order.customer_email}</a></p>
                                    ${order.customer_phone ? `<p><strong>Phone:</strong> <a href="tel:${order.customer_phone}">${order.customer_phone}</a></p>` : ''}
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">📍 Shipping Address</h6>
                                    <address style="white-space: pre-line;">${order.shipping_address}</address>
                                    ${order.billing_address && order.billing_address !== order.shipping_address ? `
                                        <h6 class="fw-bold mt-3">📍 Billing Address</h6>
                                        <address style="white-space: pre-line;">${order.billing_address}</address>
                                    ` : ''}
                                </div>
                            </div>

                            ${order.notes ? `
                                <hr>
                                <h6 class="fw-bold">📝 Order Notes</h6>
                                <div class="alert alert-light">${order.notes}</div>
                            ` : ''}

                            <!-- Today's order special info -->
                            <div class="card border-info mt-3">
                                <div class="card-body">
                                    <h6 class="fw-bold text-info">📅 Today's Order Stats</h6>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <small class="text-muted d-block">Order Age</small>
                                            <span class="badge bg-info">${new Date(order.created_at).toLocaleTimeString()}</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Processing Time</small>
                                            <span class="badge bg-warning">${order.processing_days > 0 ? `${order.processing_days} days` : 'Fresh order'}</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Progress</small>
                                            <span class="badge ${order.status_progress_class.replace('progress-', 'bg-')}">${order.status_progress}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h6 class="fw-bold">📊 Today's Performance</h6>
                            <div class="card border-primary mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Status Progress:</span>
                                        <span class="badge bg-primary">${order.status_progress}%</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: ${order.status_progress}%"></div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="small">
                                            <i class="bx bx-time me-1"></i>
                                            Order received ${new Date(order.created_at).toLocaleTimeString()}</small>
                                        </div>
                                        <div class="small ${order.status === 'pending' ? 'text-warning' : (order.status === 'dispatch' ? 'text-info' : 'text-success')} mt-1">
                                            <i class="bx ${order.status === 'pending' ? 'bx-info-circle' : (order.status === 'dispatch' ? 'bx-send' : 'bx-check')} me-1"></i>
                                            ${order.status === 'pending' ? 'Waiting for processing' : (order.status === 'dispatch' ? 'In delivery process' : 'Completed successfully')}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card border-secondary">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-3">⚡ Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary btn-sm"
                                                onclick="quickStatusChange(${order.id}, 'dispatch')"                                          ${order.status === 'dispatch' || order.status === 'delivered' ? 'disabled' : ''}>
                                            <i class="bx bx-send me-1"></i>Dispatch Order
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm"
                                                onclick="quickStatusChange(${order.id}, 'delivered')"
                                                ${order.status === 'delivered' ? 'disabled' : ''}>
                                            <i class="bx bx-check-double me-1"></i>Mark Delivered
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">🛒 Order Items</h6>
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
                `;

                        if (data.formatted_items && data.formatted_items.length > 0) {
                            data.formatted_items.forEach(item => {
                                const itemTotal = (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2);
                                html += `<tr>
                            <td>
                                <div class="fw-semibold">${item.name}</div>
                                <small class="text-muted">${item.type || 'product'}</small>
                            </td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">₹${parseFloat(item.price).toFixed(2)}</td>
                            <td class="text-end fw-semibold">₹${itemTotal}</td>
                        </tr>`;
                            });

                            // Add total row
                            const grandTotal = parseFloat(order.total_amount).toFixed(2);
                            html += `<tr class="table-light border-top border-2">
                        <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                        <td class="text-end fw-bold text-success fs-5">₹${grandTotal}</td>
                    </tr>`;
                        } else {
                            html += `<tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bx bx-box bx-lg mb-2 d-block"></i>
                            <div>No items found for this order</div>
                        </td>
                    </tr>`;
                        }

                        html += `
                            </tbody>
                        </table>
                    </div>
                `;

                        $modal.find('.modal-body').html(html);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $modal.find('.modal-body').html(`
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>
                        Failed to load order details. Please try again.
                    </div>
                `);
                    });
            });

            // Status update forms
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.matches('form[action*="/update-status"]')) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 401 || response.status === 419) {
                                    throw new Error('Your session has expired. Please refresh the page and login again.');
                                }
                                throw new Error(`Server error: ${response.status}`);
                            }
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                throw new Error('Server returned an unexpected response. You may need to login again.');
                            }
                        })
                        .then(data => {
                            if (data.success) {
                                showToast('success', `✅ Order status updated to ${data.message || 'completed'}!`);
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('error', data.message || 'Status update failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('error', error.message || 'Network error. Status not updated.');
                        });
                }
            });

            // Quick status change function
            window.quickStatusChange = function (orderId, status) {
                const statusNames = {
                    'dispatch': 'DISPATCHED',
                    'delivered': 'DELIVERED'
                };

                if (confirm(`🚀 Quick Status Change\n\nAre you sure you want to mark this order as ${statusNames[status]}?\n\n${status === 'delivered' ? '⚠️ This action cannot be undone.' : ''}`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('/orders') }}/${orderId}/update-status`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = status;

                    form.appendChild(csrfToken);
                    form.appendChild(statusInput);
                    document.body.appendChild(form);

                    showToast('info', `💫 Changing status to ${statusNames[status]}...`);
                    form.submit();
                }
            };

            // Print functionality
            document.getElementById('printOrderDetails')?.addEventListener('click', function () {
                const printWindow = window.open('', '_blank');
                const orderContent = document.getElementById('orderDetailsContent').innerHTML;

                printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Today's Order Details - Print View</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { margin: 20px; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Today's Order Details</h3>
                        <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
                    </div>
                    ${orderContent}
                </body>
                </html>
            `);
                printWindow.document.close();
            });

            // Cache busting for modals
            $('#orderDetailsModal').on('hidden.bs.modal', function () {
                $('#orderDetailsContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Loading order details...</div>
                </div>
            `);
            });

            // Toast notification system
            function showToast(type, message) {
                const existingToasts = document.querySelectorAll('.custom-toast');
                existingToasts.forEach(toast => toast.remove());

                const toastContainer = document.createElement('div');
                toastContainer.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show custom-toast position-fixed`;
                toastContainer.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 350px;
                max-width: 500px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;

                const iconMap = {
                    success: 'bx-check-circle',
                    error: 'bx-x-circle',
                    warning: 'bx-exclamation-triangle',
                    info: 'bx-info-circle'
                };

                toastContainer.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bx ${iconMap[type] || 'bx-info-circle'} me-2 fs-5"></i>
                    <div class="me-auto">${message}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                document.body.appendChild(toastContainer);

                setTimeout(() => {
                    toastContainer.classList.add('fade');
                    setTimeout(() => toastContainer.remove(), 150);
                }, 4000);
            }
        });
    </script>
@endpush