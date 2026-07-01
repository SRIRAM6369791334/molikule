@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Coupon: {{ $coupon->code }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code', $coupon->code) }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $coupon->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$coupon->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="discount_type" class="form-select @error('discount_type') is-invalid @enderror" required>
                                <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="flat" {{ $coupon->discount_type == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                            </select>
                            @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" 
                                   class="form-control @error('discount_value') is-invalid @enderror" 
                                   value="{{ old('discount_value', $coupon->discount_value) }}" required>
                            @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Cart Value</label>
                            <input type="number" step="0.01" name="min_cart_value" class="form-control" 
                                   value="{{ old('min_cart_value', $coupon->min_cart_value) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Overall Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control" 
                                   value="{{ old('usage_limit', $coupon->usage_limit) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Limit Per User</label>
                            <input type="number" name="user_limit" class="form-control" 
                                   value="{{ old('user_limit', $coupon->user_limit) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Starts At</label>
                            <input type="date" name="starts_at" class="form-control" 
                                   value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expires At</label>
                            <input type="date" name="expires_at" class="form-control" 
                                   value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-md">Update Coupon</button>
                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary w-md ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
