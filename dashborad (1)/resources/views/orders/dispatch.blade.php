@extends('layouts.app')

@section('title') Dispatched Orders | Logistics Management @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Orders @endslot
        @slot('title') Dispatched Orders @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 text-info"><i class="bx bx-send me-2"></i>In-Transit Shipments</h5>
                            <p class="text-muted small mb-0 mt-1">Orders currently with courier partners</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-soft-success btn-sm me-2" id="bulk-mark-delivered" style="display: none;">
                                    <i class="bx bx-check-double me-1"></i> Bulk Deliver
                                </button>
                                <a href="{{ route('all-orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-1"></i> Back to All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>

    @include('orders.partials.order_modals')
@endsection

@push('scripts')
    <script>
        window.ordersData = @json($orders);
        window.routes = {
            delete: "{{ url('orders') }}",
            updateStatus: "{{ url('orders') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/DispatchOrdersPage.js') }}"></script>
@include('orders.partials.order_scripts')
@endpush