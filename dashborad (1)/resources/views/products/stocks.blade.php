@extends('layouts.app')
@section('title') Stock Management | Inventory Control @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Inventory @endslot
        @slot('title') Stock Management @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card premium-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1 text-primary"><i class="mdi mdi-package-variant-closed me-1"></i> Inventory Tracking</h4>
                        <div class="btn-group mt-2" role="group" aria-label="Stock Filters">
                            <button type="button" class="btn btn-sm btn-outline-primary active filter-btn" data-filter="all">All Items</button>
                            <button type="button" class="btn btn-sm btn-outline-danger filter-btn" data-filter="low">Low Stock</button>
                            <button type="button" class="btn btn-sm btn-outline-dark filter-btn" data-filter="out">Out of Stock</button>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-soft-secondary btn-sm" onclick="location.reload()"><i class="mdi mdi-refresh"></i> Refresh</button>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">Full Catalog</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table-stock-management"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalTitle">Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="stockAdjustmentForm">
                    @csrf
                    <input type="hidden" id="adj_variant_id" name="variant_id">
                    <input type="hidden" id="adj_type" name="type">
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-soft-primary text-primary display-6 rounded-circle" id="adj_icon">
                                    <i class="mdi mdi-plus"></i>
                                </div>
                            </div>
                            <h5 id="adj_product_name">Product Name</h5>
                            <p class="text-muted">Current Quantity: <span id="adj_current_qty" class="fw-bold">0</span></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity to <span id="adj_action_text">add</span></label>
                            <input type="number" class="form-control form-control-lg" name="quantity" min="1" required placeholder="Enter quantity">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note (Optional)</label>
                            <textarea class="form-control" name="note" rows="2" placeholder="Audit trail note..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">Confirm Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const stockEndpoint = "{{ route('products.stocks-ajax') }}";
        const updateStockEndpoint = "{{ route('products.update-stock') }}";
    </script>
    <script src="{{ asset('assets/js/app/StockManagement.js') }}"></script>
@endpush
