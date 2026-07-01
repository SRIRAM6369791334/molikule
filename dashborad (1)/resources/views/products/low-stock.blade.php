@extends('layouts.app')
@section('title') Low Stock Alerts | Inventory Monitor @endsection

@section('content')
    @component('components.breadcrumb')
    @slot('li_1') Inventory @endslot
    @slot('title') Low Stock Alerts @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card premium-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1 text-danger"><i class="mdi mdi-alert-decagram me-1"></i> Critical
                            Inventory</h4>
                        <p class="text-muted mb-0">Products with Stock ≤ 10 units</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-soft-secondary btn-sm" onclick="location.reload()"><i
                                class="mdi mdi-refresh"></i> Refresh</button>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">View Full Catalog</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table-low-stock"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const endpoint = "{{ route('products.ajax', ['lowStock' => 1]) }}";
    </script>
    <script src="{{ asset('assets/js/app/LowStockPage.js') }}"></script>
@endpush