@extends('layouts.app')

@section('title') Delivered Orders | Fulfilment Success @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Orders @endslot
        @slot('title') Delivered Orders @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <!-- Analytics Cards -->
            <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
                <div class="col">
                    <div class="card shadow-sm border-0 bg-soft-success">
                        <div class="card-body p-4 text-center">
                             <i class="bx bx-check-double display-4 text-success mb-2"></i>
                             <h4 class="text-success mb-1">{{ count($orders) }}</h4>
                             <p class="text-muted small mb-0 fw-600">Total Fulfilled</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 text-center">
                             <i class="bx bx-rupee display-4 text-primary mb-2"></i>
                             <h4 class="text-primary mb-1">₹{{ number_format($stats['total_revenue'], 2) }}</h4>
                             <p class="text-muted small mb-0 fw-600">Revenue Recognized</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 text-center">
                             <i class="bx bx-tachometer display-4 text-info mb-2"></i>
                             <h4 class="text-info mb-1">{{ round($orders->avg('processing_days') ?: 0, 1) }} Days</h4>
                             <p class="text-muted small mb-0 fw-600">Avg Lead Time</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 text-success"><i class="bx bx-history me-2"></i>Fulfilment History</h5>
                            <p class="text-muted small mb-0 mt-1">Archived records of completed deliveries</p>
                        </div>
                        <div class="col-md-6 text-end">
                             <a href="{{ route('all-orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back me-1"></i> Back to Live Monitor
                             </a>
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
            delete: "{{ url('orders') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/DeliveredOrdersPage.js') }}"></script>
@include('orders.partials.order_scripts')
@endpush