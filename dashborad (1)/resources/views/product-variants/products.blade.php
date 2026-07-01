@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Products</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('products.title') }}</h4>
                    <p class="card-title-desc">{{ __('products.manage_inventory') }}</p>
                </div>
                <div class="card-body">
                    <!-- Simple Search Filter -->
                    <div class="search-filter-section bg-light rounded p-3 mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-10 col-md-8 col-12">
                                <label for="product-search" class="form-label">
                                    <i class="bx bx-search-alt me-1"></i>Search Products, Brands & Categories
                                </label>
                                <input type="text" class="form-control" id="product-search"
                                    placeholder="Enter product name, brand name, or category name..."
                                    value="{{ $filters['search'] ?? '' }}">
                            </div>

                            <div class="col-lg-2 col-md-4 col-12 d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-primary" id="apply-search">
                                    <i class="bx bx-search me-1"></i>Search
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clear-search">
                                    <i class="bx bx-refresh me-1"></i>Clear
                                </button>
                            </div>
                        </div>

                        <!-- Active Search Display -->
                        <div class="active-search mt-3" id="active-search-display">
                            <!-- Dynamically populated by JavaScript -->
                        </div>
                    </div>


                    <!-- Stats Cards Row -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <span class="avatar-title bg-primary rounded-circle">
                                                <i class="bx bx-package font-size-16"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1">Total Products</h6>
                                    <h4 class="mb-0">{{ $filterStats['total_products'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <span class="avatar-title bg-success rounded-circle">
                                                <i class="bx bx-check-circle font-size-16"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1">In Stock</h6>
                                    <h4 class="mb-0">{{ $filterStats['in_stock_count'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <span class="avatar-title bg-warning rounded-circle">
                                                <i class="bx bx-info-circle font-size-16"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1">Low Stock</h6>
                                    <h4 class="mb-0">{{ $filterStats['low_stock_count'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger">
                                <div class="card-body text-center py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mx-auto mb-2">
                                            <span class="avatar-title bg-danger rounded-circle">
                                                <i class="bx bx-x-circle font-size-16"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-1">Out of Stock</h6>
                                    <h4 class="mb-0">{{ $filterStats['out_of_stock_count'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons & Project Stats -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 justify-content-end" style="width: 151%;">


                                <!-- Bulk Actions with Animations -->
                                <a href="{{ route('products.create.form') }}" class="btn btn-primary animate-btn">
                                    <i class="bx bx-plus-circle me-1"></i>Add Product
                                </a>
                                </a>
                                <a href="{{ route('product-variants.index') }}" class="btn btn-primary animate-btn">
                                    <i class="bx bx-package me-1"></i>Variants
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modern Products Table -->
                    <div class="table-responsive position-relative">
                        <table class="table table-hover align-middle mb-0 modern-table">
                            <thead class="table-primary">
                                <tr>
                                    <th class="border-0 fw-bold text-primary" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input table-check-all" type="checkbox" id="check-all">
                                            <label class="form-check-label visually-hidden" for="check-all">Select
                                                All</label>
                                        </div>
                                    </th>
                                    <th class="border-0 fw-bold text-primary">Product Details</th>
                                    <th class="border-0 fw-bold text-primary">Category & Brand</th>
                                    <th class="border-0 fw-bold text-primary text-center">Pricing</th>
                                    <th class="border-0 fw-bold text-primary text-center">Stock Status</th>
                                    <th class="border-0 fw-bold text-primary text-center">Live Status</th>
                                    <th class="border-0 fw-bold text-primary text-center" style="width: 140px;">Quick
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body">
                                @if(isset($products) && $products->count() > 0)
                                    @foreach($products as $product)
                                        <tr class="product-row animate-fade-in shadow-sm"
                                            data-product-id="{{ $product->product_id }}"
                                            style="border-radius: 8px; overflow: hidden; transition: all 0.3s ease;">
                                            <td class="border-0">
                                                <div class="form-check">
                                                    <input class="form-check-input product-checkbox table-checkbox" type="checkbox"
                                                        value="{{ $product->product_id }}">
                                                    <label class="form-check-label visually-hidden">Select this product</label>
                                                </div>
                                            </td>

                                            <!-- Product Details Cell -->
                                            <td class="border-0">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-image-container">
                                                        <img src="{{$product->image_url}}" alt="{{ $product->name }}"
                                                            class="product-thumbnail rounded shadow-sm"
                                                            style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #f1f3f4;">
                                                        @if($product->stock_quantity <= 5)
                                                            <span
                                                                class="badge bg-danger position-absolute top-0 start-100 translate-middle badge-dot"></span>
                                                        @endif
                                                    </div>
                                                    <div class="product-info">
                                                        <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($product->name, 40) }}</h6>
                                                        <p class="mb-0 small text-muted">
                                                            {{ strip_tags(Str::limit($product->description ?? 'No description', 45)) }}
                                                        </p>
                                                        <small class="text-muted">ID: #{{ $product->product_id }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Category & Brand -->
                                            <td class="border-0">
                                                <div class="category-brand-info">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="bx bx-category text-muted me-2"></i>
                                                        <span class="badge bg-light text-dark rounded-pill px-3 py-1">
                                                            {{ $product->category->category_name ?? 'Uncategorized' }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-tag text-muted me-2"></i>
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                                                            {{ $product->brand->brand_name ?? 'No Brand' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Pricing -->
                                            <td class="border-0 text-center">
                                                <div class="pricing-cell">
                                                    <div class="fw-bold text-success mb-1 primary-price">
                                                        ₹{{ number_format($product->price, 2) }}</div>
                                                </div>
                                            </td>

                                            <!-- Stock Status -->
                                            <td class="border-0 text-center">
                                                <div class="stock-status-cell">
                                                    @if($product->stock_quantity > 15)
                                                        <span
                                                            class="badge bg-success-subtle text-success px-3 py-2 fw-semibold rounded-pill">
                                                            <i class="bx bx-check-circle me-1"></i>{{ $product->stock_quantity }} In
                                                            Stock
                                                        </span>
                                                    @elseif($product->stock_quantity > 0)
                                                        <span
                                                            class="badge bg-warning-subtle text-warning px-3 py-2 fw-semibold rounded-pill">
                                                            <i class="bx bx-info-circle me-1"></i>{{ $product->stock_quantity }} Low
                                                            Stock
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger-subtle text-danger px-3 py-2 fw-semibold rounded-pill">
                                                            <i class="bx bx-x-circle me-1"></i>Out of Stock
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Live Status Toggle -->
                                            <td class="border-0 text-center">
                                                <div class="status-toggle-cell">
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input status-toggle modern-toggle" type="checkbox"
                                                            id="status-{{ $product->product_id }}"
                                                            data-product-id="{{ $product->product_id }}" {{ $product->active ? 'checked' : '' }}>
                                                        <label class="form-check-label visually-hidden"
                                                            for="status-{{ $product->product_id }}">
                                                            Toggle product status
                                                        </label>
                                                    </div>
                                                    <div class="mt-1">
                                                        <small
                                                            class="text-muted fw-semibold {{ $product->active ? 'text-success' : 'text-secondary' }}">
                                                            {{ $product->active ? 'Live' : 'Draft' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="border-0">
                                                <div class="action-buttons d-flex justify-content-center gap-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-info btn-icon action-btn animate-on-hover"
                                                        title="View Details"
                                                        onclick="window.location.href='{{ route('products.show', $product->product_id) }}'">
                                                        <i class="bx bx-show-alt fs-5"></i>
                                                    </button>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary btn-icon action-btn animate-on-hover"
                                                        title="Edit Product"
                                                        onclick="window.location.href='{{ route('products.edit', $product->product_id) }}'">
                                                        <i class="bx bx-edit fs-5"></i>
                                                    </button>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-icon action-btn delete-product animate-on-hover"
                                                        title="Delete Product" data-product-id="{{ $product->product_id }}">
                                                        <i class="bx bx-trash fs-5"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="empty-table-row">
                                        <td colspan="7" class="border-0">
                                            <div class="text-center py-5">
                                                <div class="empty-state-icon mb-4">
                                                    <i class="bx bx-package display-4 text-muted"></i>
                                                </div>
                                                <h5 class="text-muted mb-3">No Products Found</h5>
                                                <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
                                                    There are no products matching your current filters, or your inventory is
                                                    empty. Start building your catalog!
                                                </p>
                                                <a href="{{ route('products.create.form') }}"
                                                    class="btn btn-primary btn-lg animate-btn">
                                                    <i class="bx bx-plus-circle me-2"></i>Create Your First Product
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- Loading Animation -->
                        <div class="loading-overlay d-none" id="loading-overlay">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Processing your request...</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Pagination with Load More Option -->
                    @if($products && $products->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4"
                            style="border-top: 1px solid rgba(222, 226, 230, 0.5); padding-top: 16px; font-family: 'Courier New', monospace;">
                            <div class="text-muted">
                                <small style="font-size: 14px;">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Showing {{ $products->count() }} of {{ $products->total() }} products
                                    ({{ $products->currentPage() }} of {{ $products->lastPage() }})
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <!-- Load More Button (in addition to pagination) -->
                                @if($products->hasMorePages())
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="load-more-btn">
                                        <i class="bx bx-plus-circle me-1"></i>Load More
                                    </button>
                                @endif
                                <div class="custom-pagination">
                                    {{ $products->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>

                        <!-- Loading Indicator for Load More -->
                        <div class="text-center mt-3 d-none" id="load-more-loading">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <small class="text-muted ms-2">Loading more products...</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Apply Table Fonts Throughout the Page */
        .table-responsive,
        .products-page,
        .enhanced-pagination-container,
        .pagination-stats,
        .pagination-controls-wrapper,
        .card-body,
        .filter-row {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Enhanced Typography for Consistency */
        .card-header .card-title,
        .filter-labels,
        .form-label,
        .text-muted,
        .pagination-stats,
        .page-info,
        .btn-text {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 500;
        }

        /* Table Header Enhancement */
        .modern-table thead th {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Table Body Typography */
        .modern-table tbody td,
        .product-title,
        .status-badge,
        .price-display,
        .stock-info {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
        }

        /* Enhanced Pagination Hover Effects */
        .pagination-nav-btn:hover {
            background: linear-gradient(135deg, #007bff, #6610f2) !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }

        .pagination-nav-btn:active {
            transform: translateY(0) scale(0.98);
        }

        /* Statistics Display Enhancement */
        .pagination-stats .fw-bold {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            color: #007bff !important;
        }

        /* Filter Elements Typography */
        .form-select,
        .form-control {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 400;
        }

        /* Badge Enhancements */
        .badge {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Button Text Consistency */
        .btn {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 500;
        }

        /* Product Names and Details */
        .product-title,
        .product-description,
        .product-id {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
        }

        /* Statistics Cards */
        .stats-card-title,
        .stats-card-value {
            font-family: 'Courier New', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
            font-weight: 600;
        }

        /* Fluid Animation for Page Load */
        .fade-in-on-load {
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards 0.2s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Typography Adjustments */
        @media (max-width: 768px) {

            .table-responsive,
            .products-page,
            .card-body {
                font-size: 14px !important;
            }

            .pagination-stats {
                font-size: 13px !important;
            }
        }

        @media (max-width: 576px) {

            .products-page,
            .card-body {
                font-size: 13px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modern animations and functionality for the products table

            // Add button animations
            function animateButton(btn, action = 'pulse') {
                btn.classList.add('animate__animated', `animate__${action}`);
                btn.addEventListener('animationend', function () {
                    btn.classList.remove('animate__animated', `animate__${action}`);
                }, { once: true });
            }

            // Enhanced bulk operations system
            const bulkActions = document.getElementById('bulk-actions');
            const selectionCounter = document.getElementById('selection-counter');

            function updateSelectionState() {
                const checkboxes = document.querySelectorAll('.product-checkbox:checked');
                const selectedCount = checkboxes.length;

                // Update select all checkbox
                const selectAllCheckbox = document.getElementById('check-all');
                const allCheckboxes = document.querySelectorAll('.product-checkbox');
                const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allCheckboxes.length > 0 && checkedBoxes.length === allCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < allCheckboxes.length;
                }

                // Update bulk actions visibility
                if (bulkActions && selectedCount > 0) {
                    bulkActions.style.opacity = '1';
                    bulkActions.style.pointerEvents = 'auto';
                    document.querySelectorAll('.animate-btn').forEach(btn => btn.disabled = false);

                    // Update selection counter
                    if (selectionCounter) {
                        selectionCounter.textContent = `${selectedCount} selected`;
                        selectionCounter.classList.add('fw-bold', 'text-primary');
                    }

                    // Highlight selected rows with smooth animation
                    document.querySelectorAll('.product-row').forEach(row => {
                        const checkbox = row.querySelector('.product-checkbox');
                        if (checkbox?.checked) {
                            row.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
                            row.style.border = '1px solid rgba(13, 110, 253, 0.15)';
                        } else {
                            row.style.backgroundColor = '';
                            row.style.border = '';
                        }
                    });

                } else if (bulkActions) {
                    bulkActions.style.opacity = '0.5';
                    bulkActions.style.pointerEvents = 'none';
                    document.querySelectorAll('.animate-btn').forEach(btn => btn.disabled = true);

                    if (selectionCounter) {
                        selectionCounter.textContent = '0 selected';
                        selectionCounter.classList.remove('fw-bold', 'text-primary');
                    }

                    // Reset all row highlighting
                    document.querySelectorAll('.product-row').forEach(row => {
                        row.style.backgroundColor = '';
                        row.style.border = '';
                    });
                }
            }

            // Select all functionality with enhanced feedback
            document.getElementById('check-all')?.addEventListener('change', function () {
                const isChecked = this.checked;
                const checkboxes = document.querySelectorAll('.product-checkbox');

                // Animate checkbox changes with delay for better UX
                checkboxes.forEach((cb, index) => {
                    setTimeout(() => {
                        cb.checked = isChecked;
                        // Animate the checkbox parent
                        const row = cb.closest('.product-row');
                        animateButton(row, 'fadeIn');
                    }, index * 20); // Stagger animations
                });

                // Update state after all checkboxes are processed
                setTimeout(updateSelectionState, checkboxes.length * 20 + 100);
            });

            // Individual checkbox changes with immediate feedback
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateSelectionState();

                    // Animate the checkbox's row
                    const row = this.closest('.product-row');
                    animateButton(row, 'pulse');
                });
            });

            // Modern bulk action handlers with loading states
            const bulkButtons = ['bulk-activate', 'bulk-deactivate', 'bulk-delete'];

            bulkButtons.forEach(action => {
                document.getElementById(action)?.addEventListener('click', () => executeBulkAction(action));
            });

            function executeBulkAction(action) {
                const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
                const selectedRows = selectedIds.length;

                if (selectedRows === 0) {
                    showToast('warning', 'Please select at least one product first.');
                    return;
                }

                // Confirmation dialogs with better UX
                const confirmations = {
                    delete: `Are you sure you want to delete ${selectedRows} product${selectedRows > 1 ? 's' : ''}? This action cannot be undone.`,
                    activate: `Activate ${selectedRows} selected product${selectedRows > 1 ? 's' : ''}?`,
                    deactivate: `Deactivate ${selectedRows} selected product${selectedRows > 1 ? 's' : ''}?`
                };

                if (action !== 'export' && !confirm(confirmations[action] || `Execute ${action} on selected products?`)) {
                    return;
                }
            }

            // Enhanced delete functionality with event delegation
            document.addEventListener('click', function (e) {
                if (e.target.closest('.delete-product')) {
                    e.preventDefault();
                    const btn = e.target.closest('.delete-product');
                    const productId = btn.dataset.productId;
                    const productRow = btn.closest('.product-row');

                    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                        // Show loading state immediately
                        const loadingOverlay = document.getElementById('loading-overlay');
                        if (loadingOverlay) {
                            loadingOverlay.classList.remove('d-none');
                        }

                        // Execute delete immediately (no animation delay)
                        fetch(`{{ url('/products') }}/${productId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => {
                                // Always parse JSON response regardless of status code
                                return response.json().then(data => ({
                                    status: response.status,
                                    ok: response.ok,
                                    data: data
                                }));
                            })
                            .then(result => {
                                if (loadingOverlay) {
                                    loadingOverlay.classList.add('d-none');
                                }

                                // Handle both success and error responses
                                if (result.data.success === true) {
                                    showToast('success', 'Product deleted successfully');
                                    // Animate out then reload
                                    if (productRow) {
                                        animateButton(productRow, 'bounceOut');
                                        setTimeout(() => location.reload(), 500);
                                    } else {
                                        setTimeout(() => location.reload(), 500);
                                    }
                                } else {
                                    // Gracefully handle error responses (including 404)
                                    showToast('error', result.data.message || 'Product could not be deleted');
                                }
                            })
                            .catch(error => {
                                console.error('Delete error:', error);
                                if (loadingOverlay) {
                                    loadingOverlay.classList.add('d-none');
                                }
                                showToast('error', error.message || 'Error deleting product');
                            });
                    }
                }
            });

            // Action button hover effects
            document.querySelectorAll('.animate-on-hover').forEach(btn => {
                btn.addEventListener('mouseenter', function () {
                    this.style.transform = 'scale(1.1)';
                });
                btn.addEventListener('mouseleave', function () {
                    this.style.transform = 'scale(1)';
                });
            });

            // Row hover effects
            document.querySelectorAll('.product-row').forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
                    this.style.transform = 'translateY(-2px)';
                });
                row.addEventListener('mouseleave', function () {
                    this.style.boxShadow = '';
                    this.style.transform = '';
                });
            });

            // Enhanced toast notification system without document.write
            function showToast(type, message) {
                // Remove any existing toasts first
                const existingToasts = document.querySelectorAll('.custom-toast');
                existingToasts.forEach(toast => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                });

                // Create toast elements programmatically (no innerHTML)
                const toastContainer = document.createElement('div');
                toastContainer.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show custom-toast`;
                toastContainer.setAttribute('role', 'alert');
                toastContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 350px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border: none;
                font-family: 'Courier New', monospace;
                font-weight: 500;
            `;

                // Create flex container for content
                const contentContainer = document.createElement('div');
                contentContainer.className = 'd-flex align-items-center';
                toastContainer.appendChild(contentContainer);

                // Add icon
                const iconElement = document.createElement('i');
                iconElement.className = `bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2 fs-5`;
                contentContainer.appendChild(iconElement);

                // Add message
                const messageElement = document.createElement('div');
                messageElement.className = 'me-auto';
                messageElement.textContent = message;
                contentContainer.appendChild(messageElement);

                // Add close button
                const closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.className = 'btn-close';
                closeButton.setAttribute('data-bs-dismiss', 'alert');
                closeButton.setAttribute('aria-label', 'Close');
                contentContainer.appendChild(closeButton);

                // Add to DOM
                document.body.appendChild(toastContainer);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (document.body.contains(toastContainer)) {
                        // Add fade effect
                        toastContainer.classList.add('fade');
                        setTimeout(() => {
                            if (document.body.contains(toastContainer)) {
                                document.body.removeChild(toastContainer);
                            }
                        }, 150);
                    }
                }, 3000);

                // Setup close button functionality
                closeButton.addEventListener('click', () => {
                    if (document.body.contains(toastContainer)) {
                        document.body.removeChild(toastContainer);
                    }
                });
            }

            // Row fade-in animation on page load
            const productRows = document.querySelectorAll('.product-row');
            productRows.forEach((row, index) => {
                row.style.opacity = '0';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50); // Staggered animation
            });

            // Initialize selection state on load
            updateSelectionState();

            // Simple search functionality
            const searchInput = document.getElementById('product-search');
            const applySearchBtn = document.getElementById('apply-search');
            const clearSearchBtn = document.getElementById('clear-search');
            let searchTimeout;

            // Apply search with debouncing
            function applySearch() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = searchInput?.value.trim() || '';

                    const params = new URLSearchParams(window.location.search);

                    if (searchTerm) {
                        params.set('search', searchTerm);
                    } else {
                        params.delete('search');
                    }

                    // Reset pagination when searching
                    params.delete('page');

                    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                    window.location.href = newUrl;

                }, 300); // 300ms debounce
            }

            // Clear search function - expose globally for onclick handlers
            window.clearSearch = function () {
                if (searchInput) {
                    searchInput.value = '';
                }
                const params = new URLSearchParams(window.location.search);
                params.delete('search');
                params.delete('page'); // Reset to first page

                const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.location.href = newUrl;
            };

            // Event listeners
            if (applySearchBtn) {
                applySearchBtn.addEventListener('click', function () {
                    animateButton(this, 'bounceIn');
                    applySearch();
                });
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function () {
                    animateButton(this, 'bounceIn');
                    clearSearch();
                });
            }

            if (searchInput) {
                // Search on Enter key
                searchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applySearch();
                    }
                });

                // Real-time search (optional - debounced)
                searchInput.addEventListener('input', function () {
                    // Auto-search on input change - uncomment if needed
                    // applySearch();
                });
            }

            // Active search display system
            function updateActiveSearchDisplay() {
                const searchTerm = searchInput?.value.trim() || '';
                const activeSearchDisplay = document.getElementById('active-search-display');

                if (!activeSearchDisplay) return;

                // Clear existing content
                activeSearchDisplay.innerHTML = '';

                if (searchTerm) {
                    const searchContainer = document.createElement('div');
                    searchContainer.className = 'd-flex flex-wrap align-items-center gap-2';

                    const label = document.createElement('small');
                    label.className = 'text-muted fw-semibold me-2 mb-2 mb-md-0';
                    label.textContent = 'Active search:';
                    searchContainer.appendChild(label);

                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2 mb-md-0';
                    badge.innerHTML = `
                    <i class="bx bx-search-alt me-1"></i>
                    <span>"${searchTerm}"</span>
                    <button type="button" class="btn-close btn-close-sm ms-2" aria-label="Clear search"
                            onclick="clearSearch()"></button>
                `;
                    searchContainer.appendChild(badge);

                    activeSearchDisplay.appendChild(searchContainer);
                }
            }

            // Update active search display on page load
            updateActiveSearchDisplay();

            // Update when search input changes (for real-time updates if enabled)
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    // Uncomment to enable real-time display updates
                    // updateActiveSearchDisplay();
                });
            }

            // Load More functionality
            const loadMoreBtn = document.getElementById('load-more-btn');
            const loadMoreLoading = document.getElementById('load-more-loading');
            const productsTableBody = document.getElementById('products-table-body');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    loadMoreProducts();
                });
            }

            function loadMoreProducts() {
                if (!loadMoreBtn || loadMoreBtn.disabled) return;

                // Get current page and increment
                const urlParams = new URLSearchParams(window.location.search);
                const currentPage = parseInt(urlParams.get('page') || '1');
                const nextPage = currentPage + 1;

                // Update URL parameter
                urlParams.set('page', nextPage.toString());
                const nextUrl = window.location.pathname + '?' + urlParams.toString();

                // Show loading state
                loadMoreBtn.disabled = true;
                loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...';
                if (loadMoreLoading) {
                    loadMoreLoading.classList.remove('d-none');
                }

                // Fetch next page
                fetch(nextUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        // Parse the HTML to extract product rows
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newRows = doc.querySelectorAll('#products-table-body .product-row');

                        if (newRows.length > 0) {
                            // Add new rows to the table with animation
                            newRows.forEach((row, index) => {
                                const clonedRow = row.cloneNode(true);
                                clonedRow.style.opacity = '0';
                                clonedRow.style.transform = 'translateY(20px)';
                                productsTableBody.appendChild(clonedRow);

                                // Animate in the new row
                                setTimeout(() => {
                                    clonedRow.style.transition = 'all 0.5s ease';
                                    clonedRow.style.opacity = '1';
                                    clonedRow.style.transform = 'translateY(0)';
                                }, index * 100);
                            });

                            // Update stats display
                            const newStats = doc.querySelector('.pagination-stats small');
                            if (newStats) {
                                const currentStats = document.querySelector('.pagination-stats small');
                                if (currentStats) {
                                    currentStats.innerHTML = newStats.innerHTML;
                                }
                            }

                            // Update load more button visibility
                            const hasMorePages = doc.querySelector('#load-more-btn');
                            if (hasMorePages) {
                                loadMoreBtn.style.display = 'inline-block';
                            } else {
                                loadMoreBtn.style.display = 'none';
                            }

                            // Update URL without reload
                            window.history.pushState({}, '', nextUrl);

                            showToast('success', `Loaded ${newRows.length} more products successfully`);
                        } else {
                            // No more products to load
                            loadMoreBtn.style.display = 'none';
                            showToast('info', 'No more products to load');
                        }
                    })
                    .catch(error => {
                        console.error('Load more error:', error);
                        showToast('error', 'Failed to load more products. Please try again.');
                    })
                    .finally(() => {
                        // Reset loading state
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.innerHTML = '<i class="bx bx-plus-circle me-1"></i>Load More';
                        if (loadMoreLoading) {
                            loadMoreLoading.classList.add('d-none');
                        }
                    });
            }

            // Product Live Status toggle functionality
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function () {
                    const productId = this.dataset.productId;
                    const isActive = this.checked;
                    const row = this.closest('tr');
                    const label = row.querySelector('small.fw-semibold');

                    // Update UI immediately (optimistic update)
                    if (label) {
                        label.textContent = isActive ? 'Live' : 'Draft';
                        label.className = `text-muted fw-semibold ${isActive ? 'text-success' : 'text-secondary'}`;
                    }

                    // Show loading state on the toggle
                    this.disabled = true;
                    this.classList.add('opacity-50');

                    // Make AJAX call to toggle status
                    fetch(`{{ url('/products') }}/${productId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ active: isActive })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                // Revert UI on failure
                                revertStatusToggle.call(this, !isActive, row, label);
                                showToast('error', data.message || 'Failed to update product status');
                            } else {
                                showToast('success', `Product ${isActive ? 'activated' : 'deactivated'} successfully`);
                            }
                        })
                        .catch(error => {
                            console.error('Status toggle error:', error);
                            // Revert UI on error
                            revertStatusToggle.call(this, !isActive, row, label);
                            showToast('error', 'Network error. Status not changed.');
                        })
                        .finally(() => {
                            // Re-enable toggle
                            this.disabled = false;
                            this.classList.remove('opacity-50');
                        });
                });
            });

            function revertStatusToggle(isActive, row, label) {
                this.checked = !isActive; // Revert checkbox
                if (label) {
                    label.textContent = !isActive ? 'Live' : 'Draft'; // Revert label
                    label.className = `text-muted fw-semibold ${!isActive ? 'text-success' : 'text-secondary'}`;
                }
            }

            // Reload page when using browser back/forward with load more
            window.addEventListener('popstate', function () {
                window.location.reload();
            });
        });
    </script>
@endpush