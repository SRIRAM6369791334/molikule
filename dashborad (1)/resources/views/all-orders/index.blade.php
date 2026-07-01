@use('Illuminate\Support\Str')
@extends('layouts.app')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Orders</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                                <li class="breadcrumb-item active">Orders</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <div class="search-box me-2 mb-2 d-inline-block">
                                        <div class="position-relative">
                                            <input type="text" class="form-control"
                                                placeholder="Search by order ID, customer name..."
                                                value="{{ request('search') }}">
                                            <i class="bx bx-search-alt search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="text-sm-end">
                                        <a href="{{ route('orders.create') }}"
                                            class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i
                                                class="mdi mdi-plus me-1"></i> Add New Order</a>
                                    </div>
                                </div><!-- end col-->
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-check">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 20px;" class="align-middle">
                                                <div class="form-check font-size-16">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                    <label class="form-check-label" for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th class="align-middle">Order ID</th>
                                            <th class="align-middle">Billing Name</th>
                                            <th class="align-middle">Email</th>
                                            <th class="align-middle">Date</th>
                                            <th class="align-middle">Total</th>
                                            <th class="align-middle">Order Status</th>
                                            <th class="align-middle">Payment Status</th>
                                            <th class="align-middle">View Details</th>
                                            <th class="align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>
                                                    <div class="form-check font-size-16">
                                                        <input class="form-check-input order-checkbox" type="checkbox"
                                                            value="{{ $order->id }}" id="orderidcheck{{$loop->index + 1}}">
                                                        <label class="form-check-label"
                                                            for="orderidcheck{{$loop->index + 1}}"></label>
                                                    </div>
                                                </td>
                                                <td><a href="{{ route('orders.show', $order->id) }}"
                                                        class="text-body fw-bold">#{{ $order->order_number ?: $order->id }}</a>
                                                </td>
                                                <td>
                                                    {{ $order->customer_name }}
                                                    <div class="mt-1">
                                                        @if($order->user_id)
                                                            <span class="badge badge-pill bg-success-subtle text-success font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>
                                                        @else
                                                            <span class="badge badge-pill bg-secondary-subtle text-secondary font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted"
                                                        title="{{ $order->customer_email }}">{{ Str::limit($order->customer_email, 25) }}</small>
                                                </td>
                                                <td>
                                                    {{ $order->created_at->format('d M, Y') }}
                                                </td>
                                                <td>
                                                    ₹{{ number_format($order->total_amount, 2) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'dispatch' => 'primary',
                                                            'delivered' => 'success'
                                                        ];
                                                        $statusColor = $statusColors[$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="badge badge-pill bg-{{ $statusColor }}-subtle text-{{ $statusColor }} font-size-12">
                                                        {{ ucfirst($order->status ?? 'unknown') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $paymentStatusColors = [
                                                            'paid' => 'success',
                                                            'pending' => 'warning',
                                                            'failed' => 'danger',
                                                            'refunded' => 'info'
                                                        ];
                                                        $paymentColor = $paymentStatusColors[strtolower($order->payment_status ?? 'pending')] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="badge badge-pill bg-{{ $paymentColor }}-subtle text-{{ $paymentColor }} font-size-12">
                                                        {{ ucfirst($order->payment_status ?? 'Pending') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm btn-rounded"
                                                        data-bs-toggle="modal" data-bs-target="#orderDetailsModal"
                                                        data-order-id="{{ $order->id }}"
                                                        onclick="loadOrderDetails({{ $order->id }})">
                                                        View Details
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <a href="{{ route('orders.edit', $order->id) }}" class="text-success"
                                                            title="Edit Order"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                        <a href="javascript:void(0);"
                                                            onclick="confirmDeleteOrder({{ $order->id }}, '{{ $order->order_number }}')"
                                                            class="text-danger" title="Delete Order"><i
                                                                class="mdi mdi-delete font-size-18"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="bx bx-shopping-bag display-4 text-muted"></i>
                                                    <h5 class="mt-3 text-muted">No orders found</h5>
                                                    <p class="text-muted">Orders will appear here once customers place them.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination pagination-rounded justify-content-end mb-2">
                                <li class="page-item{{ $orders->onFirstPage() ? ' disabled' : '' }}">
                                    <a class="page-link" href="{{ $orders->previousPageUrl() }}" aria-label="Previous">
                                        <i class="mdi mdi-chevron-left"></i>
                                    </a>
                                </li>
                                @php $currentPage = $orders->currentPage();
                                $lastPage = $orders->lastPage(); @endphp
                                @for($page = max(1, $currentPage - 2); $page <= min($lastPage, $currentPage + 2); $page++)
                                    <li class="page-item{{ $page == $currentPage ? ' active' : '' }}">
                                        <a class="page-link" href="{{ $orders->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endfor
                                <li class="page-item{{ $currentPage == $lastPage ? ' disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ $currentPage < $lastPage ? $orders->nextPageUrl() : 'javascript: void(0);' }}"
                                        aria-label="Next">
                                        <i class="mdi mdi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Order details will be loaded here by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchInput = document.querySelector('.search-box input');
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const url = '{{ route("all-orders.index") }}' + (this.value ? '?search=' + encodeURIComponent(this.value) : '');
                        window.location.href = url;
                    }, 300);
                });
            }

            // Checkboxes for bulk operations
            const checkAll = document.getElementById('checkAll');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    orderCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActionVisibility();
                });
            }

            orderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateBulkActionVisibility();
                });
            });

            function updateBulkActionVisibility() {
                const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
                const bulkActionDiv = document.querySelector('.bulk-actions');
                if (bulkActionDiv) {
                    bulkActionDiv.style.display = checkedCount > 0 ? 'block' : 'none';
                }
            }
        });

        // Load order details via AJAX
        function loadOrderDetails(orderId) {
            fetch(`/orders/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    populateOrderModal(data);
                })
                .catch(error => {
                    console.error('Error loading order:', error);
                    alert('Error loading order details');
                });
        }

        // Populate order details modal
        function populateOrderModal(order) {
            const modalBody = document.querySelector('#orderDetailsModal .modal-body');
            const modalTitle = document.querySelector('#orderDetailsModalLabel');
            if (!modalBody || !modalTitle) return;

            modalTitle.innerHTML = `<i class="bx bx-show me-2 text-primary"></i>Order Details`;

            const statusColors = {
                'pending': 'warning',
                'processing': 'info',
                'dispatch': 'primary',
                'delivered': 'success'
            };

            const paymentStatusColors = {
                'paid': 'success',
                'pending': 'warning',
                'failed': 'danger',
                'refunded': 'info'
            };

            const orderStatusColor = statusColors[order.status] || 'secondary';
            const paymentStatusColor = paymentStatusColors[order.payment_status?.toLowerCase()] || 'secondary';
            
            // Progress calculation
            const progress = order.status_progress || 10;
            const progressClass = order.status_progress_class || 'progress-affix';

            let itemsHTML = '';
            if (order.items && order.items.length > 0) {
                itemsHTML = `
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${order.items.map(item => `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-package me-2 text-muted"></i>
                                            <div>
                                                <h6 class="text-truncate mb-0">${item.item_name}</h6>
                                                <small class="text-muted">${item.itemable_type === 'App\\Models\\ProductVariant' ? 'variant' : 'product'}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${item.quantity}</td>
                                    <td>₹${parseFloat(item.unit_price).toFixed(2)}</td>
                                    <td>₹${parseFloat(item.total_price).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            }

            modalBody.innerHTML = `
            <div class="order-details-container p-2">
                <!-- 📋 Order Information -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><span class="me-2">📋</span>Order Information</h6>
                    <div class="row bg-light p-3 rounded">
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Order Number:</p>
                            <p class="fw-bold mb-3">${order.order_number || 'MOL-' + order.id}</p>
                            
                            <p class="mb-1 text-muted small">Status:</p>
                            <p class="mb-3">
                                <span class="badge bg-${orderStatusColor}-subtle text-${orderStatusColor} font-size-12">
                                    ${order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : 'Unknown'}
                                </span>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Total Amount:</p>
                            <p class="fw-bold mb-3 text-primary">₹${parseFloat(order.total_amount).toFixed(2)}</p>
                            
                            <p class="mb-1 text-muted small">Created:</p>
                            <p class="mb-0">${new Date(order.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                </div>

                <!-- 👤 Customer Information -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><span class="me-2">👤</span>Customer Information</h6>
                    <div class="row border p-3 rounded">
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Name:</p>
                            <p class="fw-bold mb-2">
                                ${order.customer_name}
                                ${order.user_id ? '<span class="badge bg-success-subtle text-success ms-2 font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>' : '<span class="badge bg-secondary-subtle text-secondary ms-2 font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>'}
                            </p>
                            <p class="mb-1 text-muted small">Email:</p>
                            <p class="mb-0 text-primary">${order.customer_email}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted small">Phone:</p>
                            <p class="fw-bold mb-0">${order.customer_phone || 'N/A'}</p>
                        </div>
                    </div>
                </div>

                <!-- 📍 Shipping Address -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><span class="me-2">📍</span>Shipping Address</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-line;">${order.shipping_address || 'N/A'}</p>
                    </div>
                </div>

                <!-- 📊 Order Summary -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><span class="me-2">📊</span>Order Summary</h6>
                    <div class="card border shadow-none mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Status:</span>
                                <span class="fw-bold text-${orderStatusColor}">${order.status}</span>
                            </div>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-${orderStatusColor}" role="progressbar" style="width: ${progress}%" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">${progress}% Complete</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 🛒 Order Items -->
                <div>
                    <h6 class="fw-bold mb-3"><span class="me-2">🛒</span>Order Items</h6>
                    ${itemsHTML}
                    <div class="mt-3 text-end p-3 bg-light rounded">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-bold">₹${(parseFloat(order.total_amount) - (parseFloat(order.shipping_cost) || 0)).toFixed(2)}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Shipping Charges:</span>
                            <span class="text-info">+ ₹${parseFloat(order.shipping_cost || 0).toFixed(2)}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Grand Total:</h5>
                            <h5 class="mb-0 text-success fw-bold">₹${parseFloat(order.total_amount).toFixed(2)}</h5>
                        </div>
                    </div>
                </div>
            </div>
        `;
        }

        // Confirm delete order
        function confirmDeleteOrder(orderId, orderNumber) {
            if (confirm(`Are you sure you want to delete order #${orderNumber}? This action cannot be undone.`)) {
                deleteOrder(orderId);
            }
        }

        // Delete order
        function deleteOrder(orderId) {
            fetch(`/orders/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting order: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting order');
                });
        }
    </script>
@endpush