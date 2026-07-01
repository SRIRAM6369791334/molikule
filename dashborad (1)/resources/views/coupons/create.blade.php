@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Create Coupon</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('coupons.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   placeholder="SUMMER50" value="{{ old('code') }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="discount_type" class="form-select @error('discount_type') is-invalid @enderror" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="flat">Flat Amount (₹)</option>
                            </select>
                            @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" 
                                   class="form-control @error('discount_value') is-invalid @enderror" 
                                   placeholder="10.00" value="{{ old('discount_value') }}" required>
                            @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Cart Value</label>
                            <input type="number" step="0.01" name="min_cart_value" class="form-control" 
                                   placeholder="499.00" value="{{ old('min_cart_value', 0) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Overall Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control" 
                                   placeholder="100" value="{{ old('usage_limit') }}">
                            <small class="text-muted">Leave empty for unlimited</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Limit Per User</label>
                            <input type="number" name="user_limit" class="form-control" 
                                   placeholder="1" value="{{ old('user_limit', 1) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Starts At</label>
                            <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expires At</label>
                            <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-md">Create Coupon</button>
                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary w-md ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0">Quick Tips</h5>
            </div>
            <div class="card-body">
                <ul class="text-muted ps-3">
                    <li class="mb-2"><strong>Percentage:</strong> Discount is calculated as a % of the cart total.</li>
                    <li class="mb-2"><strong>Flat:</strong> A fixed amount is deducted regardless of cart total.</li>
                    <li class="mb-2"><strong>Min Cart Value:</strong> The coupon will only apply if the cart subtotal is higher than this value.</li>
                    <li><strong>Expiry:</strong> Leave empty if you want the coupon to never expire.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
