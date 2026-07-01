@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="bx bx-shield me-2 text-primary"></i>
                Quote {{ $insurance->quote_number }}
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('insurance.index') }}">Insurance Quotes</a></li>
                    <li class="breadcrumb-item active">{{ $insurance->quote_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('insurance.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>Back to List
            </a>
            <a href="{{ route('insurance.print', $insurance) }}" class="btn btn-success">
                <i class="bx bx-download me-1"></i>Download PDF
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Customer Information -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Customer Name</small><br>
                        <strong>{{ $insurance->customer_name }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Email</small><br>
                        <strong>
                            <a href="mailto:{{ $insurance->customer_email }}">{{ $insurance->customer_email }}</a>
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Phone</small><br>
                        <strong>
                            @if($insurance->customer_phone)
                                <a href="tel:{{ $insurance->customer_phone }}">{{ $insurance->customer_phone }}</a>
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">PAN Number</small><br>
                        <strong>{{ $insurance->pan_number ?: '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proposer Information -->
        @if($insurance->proposer_name || $insurance->proposer_email || $insurance->proposer_mobile)
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user-check me-2"></i>Proposer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Proposer Name</small><br>
                        <strong>{{ $insurance->proposer_name ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Email</small><br>
                        <strong>
                            @if($insurance->proposer_email)
                                <a href="mailto:{{ $insurance->proposer_email }}">{{ $insurance->proposer_email }}</a>
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Mobile</small><br>
                        <strong>
                            @if($insurance->proposer_mobile)
                                <a href="tel:{{ $insurance->proposer_mobile }}">{{ $insurance->proposer_mobile }}</a>
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Alternate Mobile</small><br>
                        <strong>{{ $insurance->alternate_mobile ?: '-' }}</strong>
                    </div>
                    @if($insurance->landline_number)
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Landline</small><br>
                        <strong>{{ $insurance->landline_number }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Vehicle Information -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-car me-2"></i>Vehicle Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Make</small><br>
                        <strong>{{ $insurance->vehicle_make ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Model</small><br>
                        <strong>{{ $insurance->vehicle_model ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Variant</small><br>
                        <strong>{{ $insurance->vehicle_variant ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Registration Number</small><br>
                        <strong>{{ $insurance->registration_number ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Year of Manufacture</small><br>
                        <strong>{{ $insurance->year_manufacture ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Engine CC</small><br>
                        <strong>{{ $insurance->engine_cc ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Fuel Type</small><br>
                        <strong>{{ $insurance->fuel_type ? ucfirst($insurance->fuel_type) : '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Body Type</small><br>
                        <strong>{{ $insurance->body_type ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Vehicle Condition</small><br>
                        <strong>{{ $insurance->vehicle_condition ? ucfirst($insurance->vehicle_condition) : '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Vehicle Type</small><br>
                        <strong>{{ $insurance->vehicle_type ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Colour</small><br>
                        <strong>{{ $insurance->vehicle_colour ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Seating Capacity</small><br>
                        <strong>{{ $insurance->seating_capacity ?: '-' }}</strong>
                    </div>
                    @if($insurance->engine_number)
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Engine Number</small><br>
                        <strong>{{ $insurance->engine_number }}</strong>
                    </div>
                    @endif
                    @if($insurance->chassis_number)
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Chassis Number</small><br>
                        <strong>{{ $insurance->chassis_number }}</strong>
                    </div>
                    @endif
                    @if($insurance->gross_weight)
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Gross Weight</small><br>
                        <strong>{{ number_format($insurance->gross_weight, 2) }} kg</strong>
                    </div>
                    @endif
                    @if($insurance->first_registration_date)
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">First Registration Date</small><br>
                        <strong>{{ $insurance->first_registration_date->format('d M, Y') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coverage & Premium Information -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-receipt me-2"></i>Coverage & Premium Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Insurance Type</small><br>
                        <strong>{{ $insurance->insurance_type ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Coverage Type</small><br>
                        <strong>{{ $insurance->coverage_type ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Insured Value</small><br>
                        <strong>
                            @if($insurance->insured_value)
                                ₹{{ number_format($insurance->insured_value, 2) }}
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Vehicle Value</small><br>
                        <strong>
                            @if($insurance->vehicle_value)
                                ₹{{ number_format($insurance->vehicle_value, 2) }}
                            @else
                                -
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Quoted Premium</small><br>
                        <strong class="text-success fs-5">
                            @if($insurance->quoted_premium)
                                ₹{{ number_format($insurance->quoted_premium, 2) }}
                            @else
                                <span class="text-muted fs-6">Not Quoted</span>
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Purpose of Use</small><br>
                        <strong>{{ $insurance->purpose_use ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Area of Operation</small><br>
                        <strong>{{ $insurance->area_operation ?: '-' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted">Vehicle Financed</small><br>
                        <strong>{{ $insurance->is_financed ? 'Yes' : 'No' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($insurance->message || $insurance->admin_notes)
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-note me-2"></i>Additional Information
                </h5>
            </div>
            <div class="card-body">
                @if($insurance->message)
                <div class="mb-3">
                    <small class="text-muted">Customer Message</small><br>
                    <div class="alert alert-light mt-2">{{ $insurance->message }}</div>
                </div>
                @endif

                @if($insurance->admin_notes)
                <div class="mb-3">
                    <small class="text-muted">Admin Notes</small><br>
                    <div class="alert alert-info mt-2">{{ $insurance->admin_notes }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Quote Timeline -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bx bx-calendar me-2"></i>Quote Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <small class="text-muted">Quote Created</small><br>
                        <strong>{{ $insurance->created_at->format('d M, Y h:i A') }}</strong>
                    </div>
                    @if($insurance->quoted_at)
                    <div class="col-md-4 mb-3">
                        <small class="text-muted">Premium Quoted</small><br>
                        <strong>{{ $insurance->quoted_at->format('d M, Y h:i A') }}</strong>
                    </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <small class="text-muted">Last Updated</small><br>
                        <strong>{{ $insurance->updated_at->format('d M, Y h:i A') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons Footer -->
        <div class="card mt-3">
            <div class="card-body text-center">
                <a href="{{ route('insurance.print', $insurance) }}" class="btn btn-success btn-lg">
                    <i class="bx bx-download me-2"></i>Download Insurance Quote PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
