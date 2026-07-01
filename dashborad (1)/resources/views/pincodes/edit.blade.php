@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="bx bx-edit me-2 text-primary"></i>Edit Pincode
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pincodes.index') }}">Pincodes</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

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
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Pincode Information</h4>
                <p class="card-title-desc">Update pincode details for delivery service</p>
            </div>
            <div class="card-body">
                <form action="{{ route('pincodes.update', $pincode) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror"
                                       id="pincode" name="pincode" maxlength="10"
                                       value="{{ old('pincode', $pincode->pincode) }}" required>
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city"
                                       value="{{ old('city', $pincode->city) }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror"
                                       id="state" name="state"
                                       value="{{ old('state', $pincode->state) }}" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Fields Row -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror"
                                       id="country" name="country" value="{{ old('country', $pincode->country ?? 'India') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Default: India</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cod_charge" class="form-label">
                                    <i class="bx bx-rupee text-success me-1"></i>COD Charge
                                </label>
                                <input type="number" step="0.01" min="0" max="999999.99"
                                       class="form-control @error('cod_charge') is-invalid @enderror"
                                       id="cod_charge" name="cod_charge" value="{{ old('cod_charge', $pincode->cod_charge ?? '120.00') }}">
                                @error('cod_charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Cash on Delivery charge (₹)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Active Status Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror"
                                           type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', $pincode->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active for Delivery
                                    </label>
                                    @error('is_active')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Enable delivery to this pincode area</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-end">
                                <a href="{{ route('pincodes.index') }}" class="btn btn-secondary me-2">
                                    <i class="bx bx-arrow-back me-1"></i>Cancel
                                </a>
                                <a href="{{ route('pincodes.show', $pincode) }}" class="btn btn-info me-2">
                                    <i class="bx bx-show me-1"></i>View
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save me-1"></i>Update Pincode
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format pincode input
    const pincodeInput = document.getElementById('pincode');
    pincodeInput.addEventListener('input', function() {
        // Remove any non-numeric characters
        this.value = this.value.replace(/\D/g, '');
    });
});
</script>
@endpush
