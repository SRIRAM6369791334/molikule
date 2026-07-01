@extends('layouts.app')
@section('title') Banners | Marketing & Content @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Marketing @endslot
        @slot('title') Home Banners @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card premium-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Active Banners</h4>
                        <p class="text-muted mb-0">Displaying {{ $banners->count() ?? 0 }} of 6 allowed banners</p>
                    </div>
                    @if(($banners->count() ?? 0) < 6)
                        <a href="{{ route('banners.create') }}" class="btn btn-primary px-4">
                            <i class="mdi mdi-plus me-1"></i> Add New Banner
                        </a>
                    @else
                        <button class="btn btn-secondary disabled" title="Limit of 6 banners reached">
                            <i class="mdi mdi-lock me-1"></i> Limit Reached
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.banners = @json($banners->items());
    </script>
    <script src="{{ asset('assets/js/app/BannersPage.js') }}"></script>
@endpush
