@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pincodes</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                        <li class="breadcrumb-item active">Pincodes</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">All Pincodes</h4>
                            <p class="card-title-desc mb-0">
                                <small class="text-muted">
                                    Total: {{ $stats['total_pincodes'] }} pincodes | Active: {{ $stats['active_pincodes'] }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-8 text-end">
                            <a href="{{ route('pincodes.create') }}"
                                class="btn btn-success btn-rounded waves-effect waves-light">
                                <i class="bx bx-plus me-1"></i> Add New Pincode
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
    <script>
        window.pincodesData = @json($pincodes);
        window.pincodeStats = @json($stats);
        window.routes = {
            toggle: "{{ url('pincodes') }}",
            delete: "{{ url('pincodes') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/PincodesPage.js') }}"></script>
@endpush