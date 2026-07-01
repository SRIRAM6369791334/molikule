@extends('layouts.app')

@php
    use App\Models\Order;
@endphp

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">All Orders</h4>

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

    <!-- Success/Error Messages -->
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">All Orders</h4>
                            <p class="card-title-desc mb-0">
                                <small class="text-muted">
                                    Total: {{ $stats['total_orders'] }} orders |
                                    Pending: {{ $stats['pending_orders'] }} |
                                    Processing: {{ $stats['processing_orders'] }} |
                                    Dispatched: {{ $stats['dispatch_orders'] }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <!-- Workflow Status Navigation -->
                            <div class="btn-group" role="group">
                                <a href="{{ route('all-orders.index') }}"
                                    class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    All Orders
                                </a>
                                <a href="{{ route('pending-orders') }}"
                                    class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    Pending
                                </a>
                                <a href="{{ route('dispatch-orders.index') }}"
                                    class="btn btn-sm {{ request('status') === 'dispatch' ? 'btn-info' : 'btn-outline-info' }}">
                                    Dispatch
                                </a>
                                <a href="{{ route('delivered-orders.index') }}"
                                    class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
                                    Delivered
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search & Filter Row -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <form method="GET" action="{{ route('orders.index') }}" id="search-form">
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="order_number"
                                                    placeholder="Search by order #..."
                                                    value="{{ request('order_number') }}">
                                                <i class="bx bx-search-alt search-icon"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('orders.index') }}" id="status-filter-form">
                                        <select class="form-select select2-auto" name="status" onchange="this.form.submit()">
                                            <option value="">All Status</option>
                                            <option value="{{ Order::STATUS_PENDING }}" {{ request('status') === Order::STATUS_PENDING ? 'selected' : '' }}>
                                                {{ Order::STATUS_LABELS[Order::STATUS_PENDING] }}
                                            </option>
                                            <option value="{{ Order::STATUS_PROCESSING }}" {{ request('status') === Order::STATUS_PROCESSING ? 'selected' : '' }}>
                                                {{ Order::STATUS_LABELS[Order::STATUS_PROCESSING] }}
                                            </option>
                                            <option value="{{ Order::STATUS_DISPATCH }}" {{ request('status') === Order::STATUS_DISPATCH ? 'selected' : '' }}>
                                                {{ Order::STATUS_LABELS[Order::STATUS_DISPATCH] }}
                                            </option>
                                            <option value="{{ Order::STATUS_DELIVERY }}" {{ request('status') === Order::STATUS_DELIVERY ? 'selected' : '' }}>
                                                {{ Order::STATUS_LABELS[Order::STATUS_DELIVERY] }}
                                            </option>
                                        </select>
                                    </form>
                                </div>

                                <!-- Customer Search -->
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('orders.index') }}" id="customer-filter-form">
                                        <input type="text" class="form-control" name="customer"
                                            placeholder="Customer name..." value="{{ request('customer') }}">
                                    </form>
                                </div>

                                <!-- Clear Filters -->
                                <div class="col-md-2">
                                    @if(request('order_number') || request('status') || request('customer'))
                                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bx bx-refresh me-1"></i>Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Actions & Export -->
                        <div class="col-lg-4">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <small class="text-muted me-2" id="selected-count">0 selected</small>
                                <div class="btn-group dropstart" id="bulk-actions" style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-check-circle me-1"></i>Bulk Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" id="bulk-pending">
                                                <i
                                                    class="bx bx-time-five text-warning me-1"></i>{{ Order::STATUS_LABELS[Order::STATUS_PENDING] }}
                                            </a></li>
                                        <li><a class="dropdown-item" href="#" id="bulk-dispatch">
                                                <i
                                                    class="bx bx-send text-info me-1"></i>{{ Order::STATUS_LABELS[Order::STATUS_DISPATCH] }}
                                            </a></li>
                                        <li><a class="dropdown-item" href="#" id="bulk-delivery">
                                                <i
                                                    class="bx bx-check-double text-success me-1"></i>{{ Order::STATUS_LABELS[Order::STATUS_DELIVERY] }}
                                            </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div id="table-gridjs"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order Details Modal -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Order Details Modal -->
    @include('orders.partials.order_modals')

@endsection

    @push('scripts')
    <script>
        window.ordersData = @json($orders);
        window.orderStats = @json($stats);
        window.routes = {
            delete: "{{ url('orders') }}",
            updateStatus: "{{ url('orders') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/OrdersPage.js') }}"></script>
    <script>
        $(document).ready(function () {
                // Make status labels available in JavaScript
                const ORDER_STATUS_LABELS = {
                    '{{ Order::STATUS_PENDING }}': '{{ Order::STATUS_LABELS[Order::STATUS_PENDING] }}',
                    '{{ Order::STATUS_PROCESSING }}': '{{ Order::STATUS_LABELS[Order::STATUS_PROCESSING] }}',
                    '{{ Order::STATUS_DISPATCH }}': '{{ Order::STATUS_LABELS[Order::STATUS_DISPATCH] }}',
                    '{{ Order::STATUS_DELIVERY }}': '{{ Order::STATUS_LABELS[Order::STATUS_DELIVERY] }}'
                };

                // Real-time updates setup
                let echoChannel = null;
                let reconnectAttempts = 0;
                const maxReconnectAttempts = 5;

                window.initializeRealTimeUpdates = function() {
                    if (typeof Echo === 'undefined') {
                        // Laravel Echo not configured - real-time updates disabled
                        // This is normal if broadcasting is not set up
                        console.info('ℹ️ Real-time updates not configured. Page will refresh on status changes.');
                        return;
                    }

                    try {
                        // Listen to orders channel for all order updates
                        echoChannel = Echo.private('orders')
                            .listen('.order.status.updated', handleOrderStatusUpdate);

                        // Listen to specific order channels for individual updates
                        @foreach($orders as $order)
                            Echo.private('order.{{ $order->id }}')
                                .listen('.order.status.updated', handleOrderStatusUpdate);
                        @endforeach

                        console.log('✅ Real-time order updates initialized');
                        reconnectAttempts = 0;

                    } catch (error) {
                        console.error('❌ Failed to initialize real-time updates:', error);
                        scheduleReconnect();
                    }
                }

                function handleOrderStatusUpdate(event) {
                    console.log('Received real-time order update:', event);

                    const orderRow = document.querySelector(`tr[data-order-id="${event.order_id}"]`);
                    if (!orderRow) {
                        console.warn('Order row not found for ID:', event.order_id);
                        return;
                    }

                    // Update version attribute
                    orderRow.setAttribute('data-version', event.new_version || 1);

                    // Update status badge
                    const statusCell = orderRow.querySelector('td:nth-child(6)'); // Status column
                    if (statusCell && event.status_badge) {
                        // Replace the existing badge
                        const badgeContainer = statusCell.querySelector('.d-flex');
                        if (badgeContainer) {
                            const oldBadge = badgeContainer.querySelector('.badge');
                            if (oldBadge) {
                                oldBadge.outerHTML = event.status_badge;
                            }
                        }
                    }

                    // Update progress bar
                    const progressCell = orderRow.querySelector('td:nth-child(7)'); // Progress column
                    if (progressCell) {
                        // This would require more complex logic to update progress
                        // For now, we'll refresh the page for accurate progress display
                    }

                    // Show notification
                    showToast('info', `Order ${event.order_number} status updated to ${event.status_label}`);

                    // Optional: Refresh the page after a delay to ensure all data is consistent
                    // setTimeout(() => location.reload(), 2000);
                }

                function scheduleReconnect() {
                    if (reconnectAttempts >= maxReconnectAttempts) {
                        console.error('Max reconnection attempts reached. Giving up.');
                        return;
                    }

                    reconnectAttempts++;
                    const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000); // Exponential backoff

                    console.log(`Attempting to reconnect in ${delay}ms (attempt ${reconnectAttempts}/${maxReconnectAttempts})`);

                    setTimeout(() => {
                        initializeRealTimeUpdates();
                    }, delay);
                }

                // Handle connection loss
                if (typeof Echo !== 'undefined') {
                    Echo.connector.pusher.connection.bind('disconnected', () => {
                        console.warn('Real-time connection lost');
                        scheduleReconnect();
                    });

                    Echo.connector.pusher.connection.bind('connected', () => {
                        console.log('Real-time connection established');
                        reconnectAttempts = 0;
                    });
                }
            });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Search functionality with debouncing
                    const orderNumberForm = document.getElementById('search-form');
                    const orderNumberInput = orderNumberForm.querySelector('input[name="order_number"]');
                    const customerForm = document.getElementById('customer-filter-form');
                    const customerInput = customerForm.querySelector('input[name="customer"]');

                    let searchTimeout;
                    function applySearch() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            // Get current URL parameters
                            const url = new URL(window.location);
                            const params = new URLSearchParams(url.search);

                            // Update search parameters
                            if (orderNumberInput.value.trim()) {
                                params.set('order_number', orderNumberInput.value.trim());
                            } else {
                                params.delete('order_number');
                            }

                            if (customerInput.value.trim()) {
                                params.set('customer', customerInput.value.trim());
                            } else {
                                params.delete('customer');
                            }

                            // Keep valid filter state
                            if (params.get('status') !== null) {
                                // Status filter is preserved by the form submission
                            }

                            // Redirect with new parameters
                            const newUrl = url.pathname + (params.toString() ? '?' + params.toString() : '');
                            window.location.href = newUrl;
                        }, 500);
                    }

                    orderNumberInput.addEventListener('input', applySearch);
                    customerInput.addEventListener('input', applySearch);

                    // Select all functionality
                    let checkAll = document.getElementById('checkAll');
                    let orderCheckboxes = [];
                    const selectedCount = document.getElementById('selected-count');
                    const bulkActions = document.getElementById('bulk-actions');

                    function attachCheckboxHandlers() {
                        // Remove all existing event listeners by creating new ones
                        if (checkAll) {
                            checkAll.removeEventListener('change', handleCheckAllChange);
                            checkAll.addEventListener('change', handleCheckAllChange);
                        }

                        // Remove existing event listeners from all checkboxes
                        orderCheckboxes.forEach(checkbox => {
                            checkbox.removeEventListener('change', handleOrderCheckboxChange);
                        });

                        // Get current checkboxes and attach new listeners
                        orderCheckboxes = document.querySelectorAll('.order-checkbox');
                        orderCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', handleOrderCheckboxChange);
                        });
                    }

                    function handleCheckAllChange() {
                        orderCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateSelectedCount();
                    }

                    function handleOrderCheckboxChange() {
                        const allChecked = Array.from(orderCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(orderCheckboxes).some(cb => cb.checked);

                        if (checkAll) {
                            checkAll.checked = allChecked;
                            checkAll.indeterminate = someChecked && !allChecked;
                        }

                        updateSelectedCount();
                    }

                    function updateSelectedCount() {
                        const count = document.querySelectorAll('.order-checkbox:checked').length;
                        selectedCount.textContent = `${count} selected`;

                        if (count > 0) {
                            bulkActions.style.display = 'block';
                        } else {
                            bulkActions.style.display = 'none';
                        }
                    }

                    // Initialize checkbox handlers
                    attachCheckboxHandlers();

                    // Bulk actions
                    document.getElementById('bulk-pending').addEventListener('click', function (e) {
                        e.preventDefault();
                        executeBulkAction('{{ Order::STATUS_PENDING }}');
                    });

                    document.getElementById('bulk-dispatch').addEventListener('click', function (e) {
                        e.preventDefault();
                        executeBulkAction('{{ Order::STATUS_DISPATCH }}');
                    });

                    document.getElementById('bulk-delivery').addEventListener('click', function (e) {
                        e.preventDefault();
                        executeBulkAction('{{ Order::STATUS_DELIVERY }}');
                    });

                    function executeBulkAction(status) {
                        const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);

                        console.log('Selected order IDs:', selectedIds);
                        console.log('Target status:', status);
                        console.log('Status labels:', ORDER_STATUS_LABELS);
                        console.log('Status label for status:', ORDER_STATUS_LABELS[status]);

                        if (selectedIds.length === 0) {
                            showToast('warning', 'Please select orders first');
                            return;
                        }

                        if (!confirm(`Are you sure you want to change ${selectedIds.length} order(s) to "${ORDER_STATUS_LABELS[status]}" status?`)) {
                            return;
                        }

                        fetch('{{ url("orders/bulk-status-update") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({

                                order_ids: selectedIds,
                                status: status
                            })
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
                                    showToast('success', data.message);
                                    setTimeout(() => location.reload(), 1500);
                                } else {
                                    showToast('error', data.message || 'Bulk operation failed');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('error', error.message || 'Network error. Please try again.');
                            });
                    }

                    // Status update forms (for next status transitions)
                    document.addEventListener('submit', function (e) {
                        const form = e.target;
                        if (form.matches('form[action*="/update-status"]')) {
                            e.preventDefault();

                            const formData = new FormData(form);

                            // Add current version for optimistic locking
                            const orderRow = form.closest('tr');
                            if (orderRow) {
                                const version = orderRow.dataset.version || 1;
                                formData.append('version', version);
                            }

                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        showToast('success', data.message);
                                        // Update version in data attribute
                                        if (orderRow && data.new_version) {
                                            orderRow.dataset.version = data.new_version;
                                        }
                                        // Refresh page after short delay to show new status
                                        setTimeout(() => location.reload(), 1000);
                                    } else {
                                        if (data.error_type === 'version_conflict') {
                                            showToast('warning', data.message);
                                            // Refresh page to show latest data
                                            setTimeout(() => location.reload(), 2000);
                                        } else {
                                            showToast('error', data.message || 'Status update failed');
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('error', 'Network error. Status not updated. Please try again.');
                                });
                        }
                    });

                    // Enhanced delete confirmation
                    document.addEventListener('submit', function (e) {
                        const form = e.target;
                        if (form.matches('form[action*="/orders/"]')) {
                            const methodInput = form.querySelector('input[name="_method"]');
                            if (methodInput && methodInput.value === 'DELETE') {
                                e.preventDefault();

                                const orderRow = form.closest('tr');
                                const orderNumber = orderRow.querySelector('.order-link')?.textContent?.trim() ||
                                    `selected order`;

                                if (confirm(`🗑️ Are you sure you want to delete "${orderNumber}"? 

        This action cannot be undone and all order data will be permanently removed.`)) {
                                    // Submit the procced form
                                    form.submit();
                                }
                            }
                        }
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

                    // Initialize on page load
                    updateSelectedCount();

                    // Initialize real-time updates
                    initializeRealTimeUpdates();
                });
            </script>
            @include('orders.partials.order_scripts')
    @endpush