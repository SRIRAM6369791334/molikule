@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Insurance Quote #{{ $insurance->quote_number }}</h4>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="bx bx-printer me-1"></i>Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="insurance-quote-template">
                    <!-- Company Header -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="insurance-logo">
                                <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="Company Logo" height="50">
                            </div>
                            <h4 class="mb-1">{{ config('app.name', 'Dashboard') }}</h4>
                            <p class="text-muted mb-1">{{ config('app.company_address', '123 Business Street, City, State 12345') }}</p>
                            <p class="text-muted">{{ config('app.company_email', 'insurance@company.com') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <h2 class="mb-3">INSURANCE QUOTE</h2>
                            <div class="mb-2">
                                <strong>Quote #:</strong> {{ $insurance->quote_number }}
                            </div>
                            <div class="mb-2">
                                <strong>Quote Date:</strong> {{ $insurance->created_at->format('d M, Y') }}
                            </div>
                            <div class="mb-2">
                                <strong>Status:</strong>
                                {!! $insurance->status_badge !!}
                            </div>
                            @if($insurance->quoted_at)
                            <div>
                                <strong>Quoted On:</strong> {{ $insurance->quoted_at->format('d M, Y') }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer & Vehicle Info -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <h5 class="mb-3">Customer Details</h5>
                            <div class="mb-2">
                                <strong>{{ $insurance->customer_name }}</strong>
                            </div>
                            <p class="text-muted mb-1">{{ $insurance->customer_email }}</p>
                            <p class="text-muted mb-1">{{ $insurance->customer_phone }}</p>
                            @if($insurance->pan_number)
                                <p class="text-muted">PAN: {{ $insurance->pan_number }}</p>
                            @endif
                        </div>
                        <div class="col-6">
                            <h5 class="mb-3">Vehicle Details</h5>
                            <div class="mb-2">
                                <strong>{{ $insurance->vehicle_make }} {{ $insurance->vehicle_model }}</strong>
                            </div>
                            <p class="text-muted mb-1">Registration: {{ $insurance->registration_number ?: 'N/A' }}</p>
                            <p class="text-muted mb-1">Year: {{ $insurance->year_manufacture ?: 'N/A' }}</p>
                            <p class="text-muted mb-1">Engine: {{ $insurance->engine_cc ?: 'N/A' }} CC</p>
                            <p class="text-muted">Body Type: {{ $insurance->body_type ?: 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Proposer Information -->
                    @if($insurance->proposer_name)
                    <div class="row mb-4">
                        <div class="col-6">
                            <h5 class="mb-3">Proposer Details</h5>
                            <div class="mb-2">
                                <strong>{{ $insurance->proposer_name }}</strong>
                            </div>
                            @if($insurance->proposer_email)
                                <p class="text-muted mb-1">{{ $insurance->proposer_email }}</p>
                            @endif
                            @if($insurance->proposer_mobile || $insurance->alternate_mobile)
                                <p class="text-muted mb-1">
                                    {{ $insurance->proposer_mobile ?: '' }}
                                    @if($insurance->alternate_mobile)
                                        / {{ $insurance->alternate_mobile }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Coverage Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Coverage Details</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="bg-light" style="width: 30%;"><strong>Insurance Type:</strong></td>
                                            <td>{{ $insurance->insurance_type ?: 'Standard' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Coverage Type:</strong></td>
                                            <td>{{ $insurance->coverage_type ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Insured Value:</strong></td>
                                            <td>₹{{ number_format($insurance->insured_value ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Vehicle Value:</strong></td>
                                            <td>₹{{ number_format($insurance->vehicle_value ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Purpose of Use:</strong></td>
                                            <td>{{ ucfirst($insurance->purpose_use) ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Area of Operation:</strong></td>
                                            <td>{{ $insurance->area_operation ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Fuel Type:</strong></td>
                                            <td>{{ ucfirst($insurance->fuel_type) ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Vehicle Condition:</strong></td>
                                            <td>{{ ucfirst($insurance->vehicle_condition) ?: 'N/A' }}</td>
                                        </tr>
                                        @if($insurance->chassis_number)
                                        <tr>
                                            <td class="bg-light"><strong>Chassis Number:</strong></td>
                                            <td>{{ $insurance->chassis_number }}</td>
                                        </tr>
                                        @endif
                                        @if($insurance->engine_number)
                                        <tr>
                                            <td class="bg-light"><strong>Engine Number:</strong></td>
                                            <td>{{ $insurance->engine_number }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="bg-light"><strong>Financed Vehicle:</strong></td>
                                            <td>{{ $insurance->is_financed ? 'Yes' : 'No' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Section -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Premium Details</h6>
                                </div>
                                <div class="card-body">
                                    @if($insurance->quoted_premium)
                                        <div class="row">
                                            <div class="col-6"><strong>Quoted Premium:</strong></div>
                                            <div class="col-6 text-end fs-5 text-success">₹{{ number_format($insurance->quoted_premium, 2) }}</div>
                                        </div>
                                    @else
                                        <p class="text-center text-muted">Premium not yet quoted</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    @if($insurance->admin_notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Admin Notes</h5>
                            <div class="border rounded p-3 bg-light">
                                {{ $insurance->admin_notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Terms and Footer -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <hr>
                            <div class="text-center text-muted">
                                <small>This insurance quote is valid for 30 days from the date of issue. Terms and conditions apply.</small>
                                <br>
                                <small>{{ config('app.name', 'Dashboard') }} - Insurance Services</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .card-header,
    .btn,
    body > div:not(.container-fluid):not(.row) {
        display: none !important;
    }

    .card-body {
        margin: 0;
        padding: 20px;
    }

    .insurance-logo img {
        max-height: 60px;
    }

    table {
        font-size: 11px;
    }

    .fs-5 {
        font-size: 18px !important;
    }

    h2, h3, h4, h5, h6 {
        margin-bottom: 15px;
        margin-top: 20px;
    }

    h2:first-child {
        margin-top: 0;
    }

    .card-header {
        background: #f8f9fa !important;
        color: #495057 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

.insurance-logo img {
    max-height: 50px;
}

@media screen {
    .no-print {
        display: block;
    }
}

@media print {
    .no-print {
        display: none !important;
    }

    .row {
        margin: 0;
    }

    .col-6, .col-12 {
        padding: 0 15px;
    }

    .table-responsive {
        margin: 0;
    }
}
</style>
@endpush
