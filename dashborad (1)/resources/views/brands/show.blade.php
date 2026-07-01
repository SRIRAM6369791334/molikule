@extends('layouts.app')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ $brand->brand_name }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('dashboard') }}">{{ config('app.name', 'Molikule') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">{{ $brand->brand_name }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <!-- Brand Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($brand->logo_url)
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->brand_name }}" class="img-fluid rounded"
                                    style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 150px;">
                                    <i class="bx bx-store display-4 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-1">{{ $brand->brand_name }}</h5>

                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">Status:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-{{ $brand->is_active ? 'success' : 'danger' }}">
                                                {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </dd>

                                        <dt class="col-sm-4">Created:</dt>
                                        <dd class="col-sm-8">
                                            {{ \Carbon\Carbon::parse($brand->created_at)->format('M d, Y') }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="mb-2">
                                        <h2 class="text-primary mb-1">{{ $brand->products_count ?? 0 }}</h2>
                                        <p class="text-muted mb-0">Products</p>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-edit me-1"></i>Edit Brand
                                        </a>
                                        <a href="{{ route('brands.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="bx bx-arrow-back me-1"></i>Back to Brands
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products by this Brand -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Products by {{ $brand->brand_name }}</h4>
                    <p class="card-title-desc">All products from this brand</p>
                </div>
                <div class="card-body">
                    @if($brand->products && $brand->products->count() > 0)
                        <div class="row">
                            @foreach($brand->products as $product)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card product-card h-100">
                                        <div class="card-body">
                                            @if($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                    class="img-fluid rounded mb-2"
                                                    style="height: 150px; width: 100%; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2"
                                                    style="height: 150px;">
                                                    <i class="bx bx-image text-muted display-4"></i>
                                                </div>
                                            @endif

                                            <h6 class="mb-1">{{ $product->name }}</h6>
                                            <p class="text-muted small mb-2">{{ strip_tags(Str::limit($product->description, 80)) }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="text-success font-weight-bold">₹{{ number_format($product->mrp_price, 2) }}</span>
                                                <span class="badge bg-{{ $product->active ? 'success' : 'danger' }}">
                                                    {{ $product->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <small class="text-muted">{{ $product->stock_quantity }} in stock</small>
                                                <a href="{{ route('products.show', $product) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </div>

                                            @if($product->category)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        Category: {{ $product->category->category_name }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-package display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No products found</h5>
                            <p class="text-muted mb-4">This brand doesn't have any products yet.</p>
                            <a href="{{ route('products.create', ['brand_id' => $brand->brand_id]) }}" class="btn btn-success">
                                <i class="bx bx-plus me-1"></i>Add First Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Product card hover effects or other JavaScript can be added here
            console.log('Brand details page loaded');
        });
    </script>
@endpush