@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="bx bx-show me-2 text-info"></i>Pincode Details
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pincodes.index') }}">Pincodes</a></li>
                    <li class="breadcrumb-item active">{{ $pincode->pincode }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ $pincode->formatted_location }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pincode</label>
                            <p class="form-control-plaintext fs-4 text-primary">{{ $pincode->pincode }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div>{!! $pincode->status_badge !!}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">City</label>
                            <p class="form-control-plaintext">{{ $pincode->city }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">State</label>
                            <p class="form-control-plaintext">{{ $pincode->state }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Country</label>
                            <p class="form-control-plaintext">{{ $pincode->country ?? 'India' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bx bx-rupee text-success me-1"></i>COD Charge
                            </label>
                            <p class="form-control-plaintext text-success fs-5">
                                ₹{{ number_format($pincode->cod_charge ?? 120.00, 2) }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext">{{ $pincode->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Updated At</label>
                            <p class="form-control-plaintext">{{ $pincode->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('pincodes.edit', $pincode) }}" class="btn btn-primary">
                        <i class="bx bx-edit me-1"></i>Edit Pincode
                    </a>
                    <a href="{{ route('pincodes.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
