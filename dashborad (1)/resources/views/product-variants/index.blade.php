@extends('layouts.app')
@section('css')
<style>
    /* Premium Select2 Styling */
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 5px !important;
        border: 1px solid #ced4da !important;
        border-radius: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-dropdown {
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #34c38f !important;
    }
</style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Product Variants</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                        <li class="breadcrumb-item active">Product Variants</li>
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
                            <h4 class="card-title mb-0">All Product Variants</h4>
                            <p class="card-title-desc mb-0">
                                <small class="text-muted">
                                    Total: {{ $totalVariants ?? 0 }} variants | Active: {{ $activeVariants ?? 0 }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <!-- View Toggle Buttons -->
                            <div class="btn-group" role="group" aria-label="View toggle">
                                <input type="radio" class="btn-check" name="view-toggle" id="table-view" autocomplete="off">
                                <label class="btn btn-outline-secondary btn-sm" for="table-view">
                                    <i class="bx bx-list-ul me-1"></i>List
                                </label>

                                <input type="radio" class="btn-check" name="view-toggle" id="grid-view" autocomplete="off"
                                    checked>
                                <label class="btn btn-outline-secondary btn-sm" for="grid-view">
                                    <i class="bx bx-grid-alt me-1"></i>Grid
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('product-variants.bulk-create') }}"
                                    class="btn btn-primary btn-rounded waves-effect waves-light">
                                    <i class="bx bx-grid-alt me-1"></i> Bulk Create
                                </a>
                                <a href="{{ route('product-variants.create') }}"
                                    class="btn btn-success btn-rounded waves-effect waves-light">
                                    <i class="bx bx-plus me-1"></i> Add Single Variant
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
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('product-variants.index') }}" id="search-form">
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="search"
                                                    placeholder="Search variants or products..."
                                                    value="{{ request('search') }}">
                                                <i class="bx bx-search-alt search-icon"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Product Filter -->
                                <div class="col-md-4">
                                    <form method="GET" action="{{ route('product-variants.index') }}" id="filter-form">
                                        <select class="form-control select2" name="product_id" id="product_filter_select" onchange="this.form.submit()">
                                            <option value="">All Products</option>
                                            @foreach($products ?? [] as $product)
                                                <option
                                                    value="{{ is_object($product) ? $product->product_id : (is_array($product) ? ($product['product_id'] ?? '') : '') }}"
                                                    {{ request('product_id') == (is_object($product) ? $product->product_id : (is_array($product) ? ($product['product_id'] ?? '') : '')) ? 'selected' : '' }}>
                                                    {{ Str::limit(is_object($product) ? ($product->name ?? 'N/A') : (is_array($product) ? ($product['name'] ?? 'N/A') : 'N/A'), 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>

                                <!-- Clear Filters -->
                                <div class="col-md-2">
                                    @if(request('search') || request('product_id'))
                                        <a href="{{ route('product-variants.index') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bx bx-refresh me-1"></i>Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="col-lg-4">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <small class="text-muted me-2" id="selected-count">0 selected</small>
                                <div class="btn-group dropstart" id="bulk-actions" style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-check-circle me-1"></i>Bulk Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" id="bulk-activate">
                                                <i class="bx bx-power-off text-success me-1"></i>Activate
                                            </a></li>
                                        <li><a class="dropdown-item" href="#" id="bulk-deactivate">
                                                <i class="bx bx-pause-circle text-warning me-1"></i>Deactivate
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#" id="bulk-delete">
                                                <i class="bx bx-trash me-1"></i>Delete
                                            </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- List/Table View -->
                    <div id="table-view-container" style="display: none;">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap table-check table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;" class="align-middle">
                                            <div class="form-check font-size-16">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                                <label class="form-check-label visually-hidden" for="checkAll">Select all
                                                    variants</label>
                                            </div>
                                        </th>
                                        <th class="align-middle">#</th>
                                        <th class="align-middle">Product</th>
                                        <th class="align-middle">Variant Details</th>
                                        <th class="align-middle">Type & Price</th>
                                        <th class="align-middle">Stock</th>
                                        <th class="align-middle">Status</th>
                                        <th class="align-middle">Images</th>
                                        <th class="align-middle">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($variants ?? [] as $variant)
                                        <tr data-variant-id="{{ $variant->id }}">
                                            <td>
                                                <div class="form-check font-size-16">
                                                    <input class="form-check-input variant-checkbox" type="checkbox"
                                                        value="{{ $variant->id }}" data-variant-id="{{ $variant->id }}">
                                                    <label class="form-check-label visually-hidden">
                                                        Select variant {{ $variant->variant_name }}
                                                    </label>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($variant->product)
                                                        <div class="avatar-xs me-3">
                                                            <img src="{{ $variant->product->image_url ?: asset('assets/images/product/img-1.png') }}"
                                                                alt="{{ $variant->product->name }}" class="img-fluid rounded">
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ Str::limit($variant->product->name, 30) }}</h6>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Product removed</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $variant->variant_name }}</h6>
                                                    @if($variant->variant_description)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($variant->variant_description, 40) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span
                                                        class="badge bg-primary-subtle text-primary mb-1">{{ ucfirst($variant->variant_type ?? 'other') }}</span>
                                                    @if($variant->price > 0)
                                                        <div class="fw-bold text-success">
                                                            {{ '₹' . number_format($variant->price, 2) }}</div>
                                                    @elseif($variant->product && $variant->product->price > 0)
                                                        <small class="text-muted">Base:
                                                            ₹{{ number_format($variant->product->price, 2) }}</small>
                                                    @else
                                                        <small class="text-muted">No price set</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                {!! $variant->stock_badge ?? '<small class="text-muted">-</small>' !!}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-variant-id="{{ $variant->id }}" {{ $variant->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label visually-hidden">
                                                        Toggle {{ $variant->variant_name }} status
                                                    </label>
                                                </div>
                                                <div class="mt-1">
                                                    <small
                                                        class="text-muted fw-semibold {{ $variant->is_active ? 'text-success' : 'text-secondary' }}">
                                                        {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted">{{ $variant->image_count ?? 0 }} images</small>
                                                    @if($variant->image_url ?? false)
                                                        <i class="bx bx-image text-success ms-2" title="Has image"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-dark p-0 dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-dots-horizontal-rounded font-size-18"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            @if($variant && $variant->id)
                                                                <a class="dropdown-item"
                                                                    href="{{ route('product-variants.edit', ['product_variant' => $variant]) }}">
                                                                    <i class="bx bx-edit me-2"></i>Edit Variant
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item disabled" href="#">
                                                                    <i class="bx bx-edit me-2"></i>Edit Variant
                                                                </a>
                                                            @endif
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if($variant && $variant->id && $variant->product)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('product-variants.show', ['product_variant' => $variant]) }}">
                                                                    <i class="bx bx-show me-2"></i>Show Variant
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if($variant && $variant->id)
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('product-variants.destroy', ['product_variant' => $variant]) }}"
                                                                    class="d-inline" id="delete-form-{{ $variant->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger"
                                                                        onclick="return confirm('Are you sure you want to delete the variant "
                                                                        {{ $variant ? $variant->variant_name : 'this variant' }}"?
                                                                        This action cannot be undone.')">
                                                                        <i class="bx bx-trash me-2"></i>Delete Variant
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bx bx-layer display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted mb-2">No Variants Found</h5>
                                                    <p class="text-muted mb-4">
                                                        @if(request('search') || request('product_id'))
                                                            No variants match your current filters.
                                                            <a href="{{ route('product-variants.index') }}">Clear filters</a> to see
                                                            all variants.
                                                        @else
                                                            Get started by adding your first product variant.
                                                        @endif
                                                    </p>
                                                    <a href="{{ route('product-variants.create') }}" class="btn btn-success">
                                                        <i class="bx bx-plus me-1"></i>Add First Variant
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Grid/Card View -->
                    <div id="grid-view-container">
                        <div class="row">
                            @forelse($variants ?? [] as $variant)
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 mb-4 variant-card-wrapper"
                                    data-variant-id="{{ $variant->id }}">
                                    <div class="card h-100 variant-grid-card border {{ $variant->is_active ? 'border-success' : 'border-secondary' }}"
                                        style="min-height: 450px;">
                                        <!-- Checkbox for bulk selection -->
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <div class="form-check">
                                                <input class="form-check-input variant-checkbox" type="checkbox"
                                                    value="{{ $variant->id }}" data-variant-id="{{ $variant->id }}">
                                                <label class="form-check-label visually-hidden">Select this variant</label>
                                            </div>
                                        </div>

                                        <!-- Card Image -->
                                        <div class="card-img-container" style="height: 180px; overflow: hidden;">
                                            <img src="{{ $variant->mainImage ?? asset('assets/images/product/img-1.png') }}"
                                                alt="{{ $variant->variant_name }}" class="card-img-top"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>

                                        <div class="card-body d-flex flex-column">
                                            <!-- Status Badge -->
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span
                                                    class="badge {{ $variant->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <small class="text-muted">#{{ $variant->id }}</small>
                                            </div>

                                            <!-- Variant Name -->
                                            <h6 class="card-title mb-2">
                                                {{ Str::limit($variant->variant_name ?? 'Unnamed Variant', 35) }}</h6>

                                            <!-- Product Info -->
                                            @if($variant->product)
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">
                                                        <i
                                                            class="bx bx-package me-1"></i>{{ Str::limit($variant->product->name ?? 'Product', 25) }}
                                                    </small>
                                                </div>
                                            @endif

                                            <!-- Variant Details -->
                                            <div class="mb-3 flex-grow-1">
                                                <div class="row g-1 text-center">
                                                    <div class="col-6">
                                                        <small
                                                            class="text-muted d-block">{{ ucfirst($variant->variant_type ?? 'other') }}</small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small
                                                            class="badge bg-primary-subtle text-primary">{{ $variant->image_count ?? 0 }}
                                                            img</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Price and Stock -->
                                            <div class="mb-3">
                                                @if($variant->price > 0)
                                                    <div class="h5 text-success mb-1">₹{{ number_format($variant->price, 2) }}</div>
                                                @elseif($variant->product && $variant->product->price > 0)
                                                    <small class="text-muted">Base:
                                                        ₹{{ number_format($variant->product->price, 2) }}</small>
                                                @else
                                                    <small class="text-muted">Price not set</small>
                                                @endif
                                                <div class="mt-1">
                                                    {!! $variant->stock_badge ?? '<small class="text-muted">-</small>' !!}
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex gap-1 mt-auto" style="position: relative; z-index: 10;">
                                                @if($variant && $variant->id)
                                                    <a href="{{ route('product-variants.edit', ['product_variant' => $variant]) }}"
                                                        class="btn btn-outline-primary btn-sm flex-fill">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-primary btn-sm flex-fill disabled" disabled>
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                @endif

                                                @if($variant && $variant->id && $variant->product)
                                                    <a href="{{ route('product-variants.show', ['product_variant' => $variant]) }}"
                                                        class="btn btn-outline-info btn-sm flex-fill">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-info btn-sm flex-fill disabled" disabled>
                                                        <i class="bx bx-show"></i>
                                                    </button>
                                                @endif

                                                @if($variant && $variant->id)
                                                    <form method="POST"
                                                        action="{{ route('product-variants.destroy', ['product_variant' => $variant]) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm flex-fill"
                                                            onclick="return confirm('Delete variant \" {{ $variant->variant_name }}\"?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-outline-danger btn-sm flex-fill disabled" disabled>
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- Quick Actions -->
                                            <div class="d-flex align-items-center justify-content-center mt-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-variant-id="{{ $variant->id }}" {{ $variant->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label small text-muted ms-1">
                                                        Active
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bx bx-layer display-4 text-muted mb-3"></i>
                                            <h5 class="text-muted mb-2">No Variants Found</h5>
                                            <p class="text-muted mb-4">
                                                @if(request('search') || request('product_id'))
                                                    No variants match your current filters.
                                                    <a href="{{ route('product-variants.index') }}">Clear filters</a> to see all
                                                    variants.
                                                @else
                                                    Get started by adding your first product variant.
                                                @endif
                                            </p>
                                            <a href="{{ route('product-variants.create') }}" class="btn btn-success">
                                                <i class="bx bx-plus me-1"></i>Add First Variant
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(isset($variants) && method_exists(($variants ?? collect()), 'hasPages') && ($variants ?? collect())->hasPages())
                        {{ ($variants ?? collect())->appends(request()->query())->links('components.pagination') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#product_filter_select').select2({
                placeholder: 'Filter by Product',
                allowClear: true,
                width: '100%'
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchForm = document.getElementById('search-form');
            const searchInput = searchForm?.querySelector('input[name="search"]');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }

            // Select all functionality - Initialize variables safely
            let checkAll = document.getElementById('checkAll');
            let variantCheckboxes = [];
            const selectedCount = document.getElementById('selected-count');
            const bulkActions = document.getElementById('bulk-actions');

            function attachCheckboxHandlers() {
                // Remove all existing event listeners by creating new ones
                if (checkAll) {
                    checkAll.removeEventListener('change', handleCheckAllChange);
                    checkAll.addEventListener('change', handleCheckAllChange);
                }

                // Remove existing event listeners from all checkboxes
                variantCheckboxes.forEach(checkbox => {
                    checkbox.removeEventListener('change', handleVariantCheckboxChange);
                });

                // Get current checkboxes and attach new listeners
                variantCheckboxes = document.querySelectorAll('.variant-checkbox');
                variantCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', handleVariantCheckboxChange);
                });
            }

            function handleCheckAllChange() {
                variantCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            }

            function handleVariantCheckboxChange() {
                const allChecked = Array.from(variantCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(variantCheckboxes).some(cb => cb.checked);

                if (checkAll) {
                    checkAll.checked = allChecked;
                    checkAll.indeterminate = someChecked && !allChecked;
                }

                updateSelectedCount();
            }

            function updateSelectedCount() {
                const count = document.querySelectorAll('.variant-checkbox:checked').length;
                if (selectedCount) selectedCount.textContent = `${count} selected`;

                if (count > 0) {
                    if (bulkActions) bulkActions.style.display = 'block';
                } else {
                    if (bulkActions) bulkActions.style.display = 'none';
                }
            }

            // Status toggle functionality - works for both table and grid views
            function attachStatusToggles() {
                document.querySelectorAll('.status-toggle').forEach(toggle => {
                    toggle.removeEventListener('change', handleStatusToggle); // Remove existing listener
                    toggle.addEventListener('change', handleStatusToggle);
                });
            }

            function handleStatusToggle() {
                const variantId = this.dataset.variantId;
                const isActive = this.checked;

                // Find the correct status text element - works for both table and grid views
                let statusText;
                let statusBadge;

                // Check if we're in table view (has small text below switch)
                const tableText = this.closest('.form-check').querySelector('small');
                if (tableText) {
                    statusText = tableText;
                }

                // Check if we're in grid view (has badge in card header)
                const gridCard = this.closest('.card-body');
                if (gridCard) {
                    statusBadge = gridCard.querySelector('.badge');
                    // Also update the label text next to the switch
                    const labelText = this.parentElement.querySelector('.form-check-label');
                    if (labelText) {
                        labelText.textContent = isActive ? 'Active' : 'Inactive';
                    }
                }

                // Update UI immediately
                if (statusText) {
                    statusText.textContent = isActive ? 'Active' : 'Inactive';
                    statusText.className = `text-muted fw-semibold ${isActive ? 'text-success' : 'text-secondary'}`;
                }

                if (statusBadge) {
                    statusBadge.textContent = isActive ? 'Active' : 'Inactive';
                    statusBadge.className = `badge ${isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}`;

                    // Also update card border
                    const card = gridCard.closest('.variant-grid-card');
                    if (card) {
                        card.className = `card h-100 variant-grid-card border ${isActive ? 'border-success' : 'border-secondary'}`;
                    }
                }

                // API call
                fetch(`{{ url('product-variants') }}/${variantId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ is_active: isActive })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // Revert on failure
                            this.checked = !isActive;

                            if (statusText) {
                                statusText.textContent = !isActive ? 'Active' : 'Inactive';
                                statusText.className = `text-muted fw-semibold ${!isActive ? 'text-success' : 'text-secondary'}`;
                            }

                            if (statusBadge) {
                                statusBadge.textContent = !isActive ? 'Active' : 'Inactive';
                                statusBadge.className = `badge ${!isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}`;
                                const card = gridCard.closest('.variant-grid-card');
                                if (card) {
                                    card.className = `card h-100 variant-grid-card border ${!isActive ? 'border-success' : 'border-secondary'}`;
                                }
                                const labelText = this.parentElement.querySelector('.form-check-label');
                                if (labelText) {
                                    labelText.textContent = !isActive ? 'Active' : 'Inactive';
                                }
                            }

                            showToast('error', data.message || 'Failed to update status');
                        }
                    })
                    .catch(error => {
                        // Revert on error
                        this.checked = !isActive;

                        if (statusText) {
                            statusText.textContent = !isActive ? 'Active' : 'Inactive';
                            statusText.className = `text-muted fw-semibold ${!isActive ? 'text-success' : 'text-secondary'}`;
                        }

                        if (statusBadge) {
                            statusBadge.textContent = !isActive ? 'Active' : 'Inactive';
                            statusBadge.className = `badge ${!isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}`;
                            const card = gridCard.closest('.variant-grid-card');
                            if (card) {
                                card.className = `card h-100 variant-grid-card border ${!isActive ? 'border-success' : 'border-secondary'}`;
                            }
                            const labelText = this.parentElement.querySelector('.form-check-label');
                            if (labelText) {
                                labelText.textContent = !isActive ? 'Active' : 'Inactive';
                            }
                        }

                        showToast('error', 'Network error. Status not changed.');
                    });
            }

            // Delete variant functionality - initialize properly for both views
            function attachDeleteHandlers() {
                // Handle delete buttons in table view (dropdown items within forms)
                document.querySelectorAll('#table-view-container .dropdown-item.text-danger').forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        handleDeleteClick(this, e);
                    });
                });

                // Handle delete buttons in grid view (form buttons)
                document.querySelectorAll('#grid-view-container form[action*="product-variants"]').forEach(form => {
                    form.querySelector('button[type="submit"]').addEventListener('click', function (e) {
                        handleDeleteClick(this, e);
                    });
                });
            }

            function handleDeleteClick(element, e) {
                e.preventDefault();

                // Find the form and get variant info
                const form = element.tagName === 'FORM' ? element : element.closest('form');
                let variantId, variantName;

                if (form) {
                    // Extract variant ID from form action URL
                    const actionUrl = form.action;
                    if (actionUrl) {
                        variantId = actionUrl.split('/').pop();

                        // Get variant name from nearby card or table row
                        const container = form.closest('.variant-card-wrapper') || form.closest('tr');
                        if (container) {
                            // Try to get variant name from card title or table cell
                            const nameElement = container.querySelector('.card-title') ||
                                container.querySelector('h6.mb-1') ||
                                container.querySelector('td div h6');
                            if (nameElement) {
                                variantName = nameElement.textContent?.trim() || 'this variant';
                            }
                        } else {
                            variantName = 'this variant';
                        }
                    }
                }

                // Find variant name in a more reliable way
                const variantContainer = form?.closest('.variant-card-wrapper') || form?.closest('tr');
                let foundName = 'this variant';

                if (variantContainer) {
                    // For grid view - look for card title
                    const gridTitle = variantContainer.querySelector('.card-title');
                    if (gridTitle) {
                        foundName = gridTitle.textContent?.trim().replace(/^Variant \d+: /, '') || 'this variant';
                    }

                    // For table view - look for variant name in table cell
                    const tableName = variantContainer.querySelector('h6.mb-1');
                    if (tableName) {
                        foundName = tableName.textContent?.trim() || 'this variant';
                    }
                }

                if (confirm(`Are you sure you want to delete the variant "${foundName}"? This action cannot be undone.`)) {
                    // Submit the form normally
                    if (form) {
                        form.submit();
                    }
                }
            }

            // Bulk actions
            function attachBulkActionHandlers() {
                document.getElementById('bulk-activate')?.addEventListener('click', function (e) {
                    e.preventDefault();
                    executeBulkAction('activate');
                });

                document.getElementById('bulk-deactivate')?.addEventListener('click', function (e) {
                    e.preventDefault();
                    executeBulkAction('deactivate');
                });

                document.getElementById('bulk-delete')?.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete the selected variants? This action cannot be undone.')) {
                        executeBulkAction('delete');
                    }
                });
            }

            function executeBulkAction(action) {
                const selectedIds = Array.from(document.querySelectorAll('.variant-checkbox:checked')).map(cb => cb.value);

                if (selectedIds.length === 0) {
                    showToast('warning', 'Please select variants first');
                    return;
                }

                fetch('{{ url("product-variants/bulk-update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        ids: selectedIds,
                        action: action
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast('error', data.message || 'Bulk operation failed');
                        }
                    })
                    .catch(error => {
                        showToast('error', 'Network error. Please try again.');
                    });
            }

            // View toggle functionality (List vs Grid)
            const tableViewRadio = document.getElementById('table-view');
            const gridViewRadio = document.getElementById('grid-view');
            const tableContainer = document.getElementById('table-view-container');
            const gridContainer = document.getElementById('grid-view-container');

            function toggleView() {
                // First, fully detach all existing handlers to prevent duplicates
                detachAllHandlers();

                if (gridViewRadio.checked) {
                    // Switch to grid view (this is the DEFAULT view)
                    if (tableContainer) tableContainer.style.display = 'none';
                    if (gridContainer) gridContainer.style.display = 'block';
                    // Hide Select All (not applicable in grid view)
                    if (checkAll) {
                        checkAll.style.display = 'none';
                        checkAll.checked = false;
                        checkAll.indeterminate = false;
                    }
                    // Update bulk actions visibility for grid view
                    const checkedInGrid = document.querySelectorAll('#grid-view-container .variant-checkbox:checked').length;
                    if (selectedCount) selectedCount.textContent = `${checkedInGrid} selected`;
                    if (bulkActions) {
                        if (checkedInGrid > 0) {
                            bulkActions.style.display = 'block';
                        } else {
                            bulkActions.style.display = 'none';
                        }
                    }
                } else {
                    // Switch to table view
                    if (tableContainer) tableContainer.style.display = 'block';
                    if (gridContainer) gridContainer.style.display = 'none';
                    // Show Select All for table view
                    if (checkAll) {
                        checkAll.style.display = 'block';
                        checkAll.checked = false;
                        checkAll.indeterminate = false;
                    }
                    // Update bulk actions visibility for table view
                    updateSelectedCount();
                }

                // Reattach all handlers for the new view
                attachCheckboxHandlers();
                attachStatusToggles();
                attachDeleteHandlers();
                attachBulkActionHandlers();

                // Ensure proper initial state
                updateSelectedCount();
            }

            // Function to safely detach all event handlers
            function detachAllHandlers() {
                // Handle checkAll checkbox safely
                if (checkAll) {
                    checkAll.removeEventListener('change', handleCheckAllChange);

                    // Clear checkbox states
                    checkAll.checked = false;
                    checkAll.indeterminate = false;
                }

                // Remove handlers from all existing checkboxes
                variantCheckboxes.forEach(checkbox => {
                    checkbox.removeEventListener('change', handleVariantCheckboxChange);
                });
                variantCheckboxes = [];

                // Remove status toggle handlers
                document.querySelectorAll('.status-toggle').forEach(toggle => {
                    toggle.removeEventListener('change', handleStatusToggle);
                });

                // Remove delete button handlers
                document.querySelectorAll('#table-view-container .dropdown-item[data-bs-toggle="modal"]').forEach(btn => {
                    btn.removeEventListener('click', handleDeleteClick);
                });
                document.querySelectorAll('#grid-view-container form[action*="product-variants"]').forEach(form => {
                    form.querySelector('button[type="submit"]').removeEventListener('click', handleDeleteClick);
                });

                // Remove bulk action handlers
                document.getElementById('bulk-activate')?.removeEventListener('click', handleBulkActivate);
                document.getElementById('bulk-deactivate')?.removeEventListener('click', handleBulkDeactivate);
                document.getElementById('bulk-delete')?.removeEventListener('click', handleBulkDelete);
            }

            // Bulk action handlers
            function handleBulkActivate() {
                executeBulkAction('activate');
            }

            function handleBulkDeactivate() {
                executeBulkAction('deactivate');
            }

            function handleBulkDelete() {
                if (confirm('Are you sure you want to delete the selected variants? This action cannot be undone.')) {
                    executeBulkAction('delete');
                }
            }

            // Safely remove handlers from an element
            function safeRemoveEventListener(element, event, handler) {
                if (element && typeof element.removeEventListener === 'function') {
                    element.removeEventListener(event, handler);
                }
            }

            // Event listeners for view toggle
            if (tableViewRadio && gridViewRadio) {
                tableViewRadio.addEventListener('change', toggleView);
                gridViewRadio.addEventListener('change', toggleView);
            }

            // Initialize view on page load (default to grid)
            toggleView();

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
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;

                toastContainer.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2 fs-5"></i>
                <div class="me-auto">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                document.body.appendChild(toastContainer);

                setTimeout(() => {
                    toastContainer.classList.add('fade');
                    setTimeout(() => toastContainer.remove(), 150);
                }, 3000);
            }
        });
    </script>
@endpush