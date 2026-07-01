@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Welcome !</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Welcome !</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<!-- Business Metrics Row -->
<div class="row g-3">
    <!-- Total Revenue -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card card-h-100 bg-primary bg-gradient border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 mb-3 lh-1 d-block text-truncate">Total Revenue</span>
                        <h4 class="mb-3 text-white">
                            ₹<span class="counter-value" data-target="{{ $totalSales ?? 0 }}">{{ number_format($totalSales ?? 0, 2) }}</span>
                        </h4>
                        <div class="text-nowrap">
                            <span class="badge bg-white bg-opacity-25 text-white">₹{{ number_format($todayRevenue ?? 0, 2) }}</span>
                            <span class="ms-1 text-white-75 font-size-13">Today's Sales</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <i class="mdi mdi-currency-inr display-4 text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <a href="{{ route('all-orders.index') }}" class="text-decoration-none">
            <div class="card card-h-100 bg-success bg-gradient border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-white-50 mb-3 lh-1 d-block text-truncate">Total Orders</span>
                            <h4 class="mb-3 text-white">
                                <span class="counter-value" data-target="{{ $totalOrders ?? 0 }}">{{ $totalOrders ?? 0 }}</span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-white bg-opacity-25 text-white">{{ $pendingOrders ?? 0 }}</span>
                                <span class="ms-1 text-white-75 font-size-13">Pending Approval</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <i class="mdi mdi-cart-arrow-down display-4 text-white opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Active Customers -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <a href="{{ route('customers') }}" class="text-decoration-none">
            <div class="card card-h-100 bg-info bg-gradient border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-white-50 mb-3 lh-1 d-block text-truncate">Active Customers</span>
                            <h4 class="mb-3 text-white">
                                <span class="counter-value" data-target="{{ $totalCustomers ?? 0 }}">{{ $totalCustomers ?? 0 }}</span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-white bg-opacity-25 text-white">100%</span>
                                <span class="ms-1 text-white-75 font-size-13">Retention Rate</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <i class="mdi mdi-account-group display-4 text-white opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Low Stock -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <a href="{{ route('products.low-stock') }}" class="text-decoration-none">
            <div class="card card-h-100 {{ ($lowStockCount ?? 0) > 0 ? 'bg-danger' : 'bg-secondary' }} bg-gradient border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-white-50 mb-3 lh-1 d-block text-truncate">Low Stock</span>
                            <h4 class="mb-3 text-white">
                                <span class="counter-value" data-target="{{ $lowStockCount ?? 0 }}">{{ $lowStockCount ?? 0 }}</span>
                            </h4>
                            <div class="text-nowrap">
                                @if(($lowStockCount ?? 0) > 0)
                                    <span class="badge bg-white bg-opacity-25 text-white">Action Required</span>
                                @else
                                    <span class="badge bg-white bg-opacity-25 text-white">Stock Optimized</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <i class="mdi mdi-alert-decagram display-4 text-white opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Catalog Metrics Row -->
<div class="row g-3 mt-1">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <a href="{{ route('products.index') }}" class="text-decoration-none">
            <div class="card card-h-100 border shadow-sm">
                <div class="card-body p-3 text-center">
                    <i class="mdi mdi-cube-outline display-6 text-primary mb-2"></i>
                    <h5 class="mb-1 counter-value" data-target="{{ $totalProducts ?? 0 }}">{{ $totalProducts ?? 0 }}</h5>
                    <p class="text-muted mb-0 font-size-13">Products</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <a href="{{ route('product-variants.index') }}" class="text-decoration-none">
            <div class="card card-h-100 border shadow-sm">
                <div class="card-body p-3 text-center">
                    <i class="mdi mdi-grid display-6 text-success mb-2"></i>
                    <h5 class="mb-1 counter-value" data-target="{{ $totalVariants ?? 0 }}">{{ $totalVariants ?? 0 }}</h5>
                    <p class="text-muted mb-0 font-size-13">Variants</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <a href="{{ route('categories.index') }}" class="text-decoration-none">
            <div class="card card-h-100 border shadow-sm">
                <div class="card-body p-3 text-center">
                    <i class="mdi mdi-folder-multiple display-6 text-info mb-2"></i>
                    <h5 class="mb-1 counter-value" data-target="{{ $totalCategories ?? 0 }}">{{ $totalCategories ?? 0 }}</h5>
                    <p class="text-muted mb-0 font-size-13">Categories</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <a href="{{ url('/brands') }}" class="text-decoration-none">
            <div class="card card-h-100 border shadow-sm">
                <div class="card-body p-3 text-center">
                    <i class="mdi mdi-tag-multiple display-6 text-warning mb-2"></i>
                    <h5 class="mb-1 counter-value" data-target="{{ $totalBrands ?? 0 }}">{{ $totalBrands ?? 0 }}</h5>
                    <p class="text-muted mb-0 font-size-13">Brands</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <a href="{{ route('banners.index') }}" class="text-decoration-none">
            <div class="card card-h-100 border shadow-sm">
                <div class="card-body p-3 text-center">
                    <i class="mdi mdi-presentation display-6 text-secondary mb-2"></i>
                    <h5 class="mb-1 counter-value" data-target="{{ \App\Models\Banner::count() }}">{{ \App\Models\Banner::count() }}</h5>
                    <p class="text-muted mb-0 font-size-13">Banners</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
        <a href="{{ route('blogs.index') }}" class="text-decoration-none">
            <div class="card card-h-100 border shadow-sm">
                <div class="card-body p-3 text-center">
                    <i class="mdi mdi-newspaper display-6 text-dark mb-2"></i>
                    <h5 class="mb-1 counter-value" data-target="{{ \App\Models\Blog::count() }}">{{ \App\Models\Blog::count() }}</h5>
                    <p class="text-muted mb-0 font-size-13">Blogs</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
