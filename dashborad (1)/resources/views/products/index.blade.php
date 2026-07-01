@extends('layouts.app')
@section('title') Products | Enterprise Dashboard @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Inventory @endslot
        @slot('title') All Products @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card premium-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-3">
                                <h4 class="card-title mb-1">Product Catalog</h4>
                                @if(request('q'))
                                    <div class="d-inline-flex align-items-center bg-white border rounded-pill shadow-sm overflow-hidden" style="border: 1px solid #e1e1e1 !important;">
                                        <div class="px-3 py-1 bg-light border-end d-flex align-items-center gap-2">
                                            <i class="bx bx-search-alt-2 text-primary"></i>
                                            <span class="text-dark small fw-semibold">Results for: <span class="text-primary">"{{ request('q') }}"</span></span>
                                        </div>
                                        <a href="{{ route('products.index') }}" class="px-3 py-1 text-danger d-flex align-items-center gap-1 hover-bg-danger-ripple transition-all" style="text-decoration: none;">
                                            <i class="bx bx-x-circle font-size-16"></i>
                                            <span class="small fw-bold">Clear</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <p class="text-muted mb-0">Manage your entire inventory and product details</p>
                        </div>
                        <div class="d-flex gap-2">
                             <a href="{{ route('product-variants.index') }}" class="btn btn-soft-info">
                                <i class="mdi mdi-layers me-1"></i> Variants
                            </a>
                            <a href="{{ route('products.create.form') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Add Product
                            </a>
                        </div>
                    </div>
                    
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.products = @json($products->values());
        window.routes = {
            delete: "{{ url('products') }}",
            toggleStatus: "{{ url('products') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/ProductsPage.js') }}"></script>
@endpush
