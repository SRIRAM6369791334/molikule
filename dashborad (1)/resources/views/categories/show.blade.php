
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ $category->category_name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->category_name }}</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <!-- Category Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}"
                                 alt="{{ $category->category_name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="bx bx-category display-4 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-1">{{ $category->category_name }}</h5>
                                <!-- <p class="text-muted mb-3">{{ $category->description ?? 'No description available' }}</p> -->

                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Status:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>

                                    <dt class="col-sm-4">Created:</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y') }}</dd></dl>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="mb-2">
                                    <h2 class="text-primary mb-1">{{ $category->products_count ?? $category->products()->count() }}</h2>
                                    <p class="text-muted mb-0">Products</p>
                                </div>

                                @if($category->parent)
                                <div class="mt-3">
                                    <small class="text-muted">Parent Category</small>
                                    <br>
                                    <span class="badge bg-info">{{ $category->parent->category_name }}</span>
                                </div>
                                @endif

                                <div class="mt-3">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary btn-sm mr-2">
                                        <i class="bx bx-edit me-1"></i>Edit Category
                                    </a>
                                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="bx bx-arrow-back me-1"></i>Back to Categories
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategories Section -->
        @if($category->children && $category->children->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">Subcategories</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($category->children as $subcategory)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                @if($subcategory->image_url)
                                    <img src="{{ $subcategory->image_url }}"
                                         alt="{{ $subcategory->category_name }}"
                                         class="img-fluid rounded mb-2"
                                         style="max-height: 100px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" style="height: 60px;">
                                        <i class="bx bx-category text-muted"></i>
                                    </div>
                                @endif
                                <h6 class="mb-1">{{ $subcategory->category_name }}</h6>
                                <small class="text-muted">{{ $subcategory->products_count ?? $subcategory->products()->count() }} products</small>
                                <div class="mt-2">
                                    <a href="{{ route('categories.show', $subcategory) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Products in this Category -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Products in {{ $category->category_name }}</h4>
                <p class="card-title-desc">All products associated with this category</p>
            </div>
            <div class="card-body">
                @if($category->products && $category->products->count() > 0)
                <div class="row">
                    @foreach($category->products as $product)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card product-card h-100">
                            <div class="card-body">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid rounded mb-2"
                                         style="height: 150px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" style="height: 150px;">
                                        <i class="bx bx-image text-muted display-4"></i>
                                    </div>
                                @endif

                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <p class="text-muted small mb-2">{{ strip_tags(Str::limit($product->description, 80)) }}</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-success font-weight-bold">₹{{ number_format($product->mrp_price, 2) }}</span>
                                    <span class="badge bg-{{ $product->active ? 'success' : 'danger' }}">
                                        {{ $product->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">{{ $product->stock_quantity }} in stock</small>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bx bx-package display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">No products in this category</h5>
                    <p class="text-muted mb-4">Start by adding products to this category.</p>
                    <a href="{{ url('/add-product') }}" class="btn btn-success">
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
document.addEventListener('DOMContentLoaded', function() {
    // Product card hover effects or other JavaScript can be added here
    console.log('Category details page loaded');
});
</script>
@endpush
