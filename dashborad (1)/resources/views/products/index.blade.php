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
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bulkUploadProductsModal">
                                <i class="mdi mdi-upload me-1"></i> Bulk Upload
                            </button>
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

    <!-- Bulk Upload Products Modal -->
    <div class="modal fade" id="bulkUploadProductsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkUploadProductsForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Upload CSV File</label>
                            <input type="file" class="form-control" name="file" accept=".csv" required>
                        </div>
                        <div class="alert alert-info py-2 small mb-3">
                            <strong>Note:</strong> Product images and variant assets will be set to optional during import. You can edit them manually later from the Edit Product interface.
                        </div>
                        <div class="text-end">
                            <a href="{{ route('products.download-template') }}" class="btn btn-link text-decoration-none p-0 small">
                                <i class="mdi mdi-download"></i> Download Sample CSV Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary bulk_submit_btn">Start Upload</button>
                    </div>
                </form>
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
