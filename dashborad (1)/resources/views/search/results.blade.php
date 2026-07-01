@extends('layouts.app')

@php
    // Helper function to determine route information based on result type
    function getRouteInfo($item) {
        if ($item instanceof \App\Models\Product) {
            return [
                'route' => 'products.show',
                'param' => $item, // Pass full model instance for route binding
                'class' => 'btn-outline-primary'
            ];
        } elseif ($item instanceof \App\Models\Category) {
            return [
                'route' => 'categories.show',
                'param' => $item, // Pass full model instance for route binding
                'class' => 'btn-outline-success'
            ];
        } elseif ($item instanceof \App\Models\Brand) {
            return [
                'route' => 'brands.show',
                'param' => $item, // Pass full model instance for route binding
                'class' => 'btn-outline-warning'
            ];
        }
        return ['route' => 'dashboard', 'param' => null, 'class' => 'btn-secondary'];
    }
@endphp

@section('title', "Search Results for '{$query}'")

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Search Results</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Search</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h5>Search results for: <strong>"{{ $query }}"</strong></h5>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('search.results') }}" class="d-flex">
                                <input type="text" name="q" class="form-control me-2" value="{{ $query }}" placeholder="Search again...">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bx bx-search-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($type === 'all' || $type === 'products')
                    @if($products->count() > 0)
                    <div class="search-section mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-package me-2"></i>Products 
                            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                ({{ $products->total() }})
                            @else
                                ({{ $products->count() }})
                            @endif
                        </h5>
                        <div class="row">
                            @foreach($products as $product)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $product->name }}</h6>
                                        @if($product->description)
                                        <p class="card-text text-muted small">{{ strip_tags(Str::limit($product->description, 100)) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $product->active ? 'success' : 'secondary' }}">
                                                {{ $product->active ? 'Active' : 'Draft' }}
                                            </span>
                                            @if($product->mrp_price)
                                            <span class="fw-bold">₹{{ number_format($product->mrp_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="mt-2">
                                            @php
                                                $routeInfo = getRouteInfo($product);
                                            @endphp
                                            <a href="{{ route($routeInfo['route'], $routeInfo['param']) }}" class="btn btn-sm {{ $routeInfo['class'] }}">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-3">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                    @endif
                    @endif

                    @if($type === 'all' || $type === 'categories')
                    @if($categories->count() > 0)
                    <div class="search-section mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-category me-2"></i>Categories 
                            @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                ({{ $categories->total() }})
                            @else
                                ({{ $categories->count() }})
                            @endif
                        </h5>
                        <div class="row">
                            @foreach($categories as $category)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $category->category_name }}</h6>
                                        @if($category->description)
                                        <p class="card-text text-muted small">{{ strip_tags(Str::limit($category->description, 100)) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            @php
                                                $routeInfo = getRouteInfo($category);
                                            @endphp
                                            <a href="{{ route($routeInfo['route'], $routeInfo['param']) }}" class="btn btn-sm {{ $routeInfo['class'] }}">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-3">
                            {{ $categories->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                    @endif
                    @endif

                    @if($type === 'all' || $type === 'brands')
                    @if($brands->count() > 0)
                    <div class="search-section mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-purchase-tag me-2"></i>Brands 
                            @if($brands instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                ({{ $brands->total() }})
                            @else
                                ({{ $brands->count() }})
                            @endif
                        </h5>
                        <div class="row">
                            @foreach($brands as $brand)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $brand->brand_name }}</h6>
                                        @if($brand->description)
                                        <p class="card-text text-muted small">{{ strip_tags(Str::limit($brand->description, 100)) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $brand->is_active ? 'success' : 'secondary' }}">
                                                {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            @php
                                                $routeInfo = getRouteInfo($brand);
                                            @endphp
                                            <a href="{{ route($routeInfo['route'], $routeInfo['param']) }}" class="btn btn-sm {{ $routeInfo['class'] }}">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($brands instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-3">
                            {{ $brands->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                    @endif
                    @endif

                    @if($products->count() === 0 && $categories->count() === 0 && $brands->count() === 0)
                    <div class="text-center py-5">
                        <i class="bx bx-search-alt-2 display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No results found</h4>
                        <p class="text-muted">Try searching with different keywords or check your spelling.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.search-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}
.search-section:last-child {
    border-bottom: none;
}
</style>
@endsection
