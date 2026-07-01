@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Coupon Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">All Coupons</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus me-1"></i> Add New Coupon
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Min Cart</th>
                                <th>Validity</th>
                                <th>Usage</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary font-size-12">{{ $coupon->code }}</span></td>
                                <td>{{ ucfirst($coupon->discount_type) }}</td>
                                <td>
                                    {{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : '₹' . number_format($coupon->discount_value, 2) }}
                                </td>
                                <td>₹{{ number_format($coupon->min_cart_value, 2) }}</td>
                                <td>
                                    <small class="d-block">Starts: {{ $coupon->starts_at ? $coupon->starts_at->format('d M, Y') : 'N/A' }}</small>
                                    <small class="d-block">Expires: {{ $coupon->expires_at ? $coupon->expires_at->format('d M, Y') : 'Never' }}</small>
                                </td>
                                <td>
                                    <div class="text-muted font-size-12">
                                        Used: {{ $coupon->usages_count ?? 0 }} / {{ $coupon->usage_limit ?: '∞' }}
                                    </div>
                                    <div class="progress progress-sm mt-1" style="height: 5px;">
                                        @php
                                            $percent = $coupon->usage_limit ? (($coupon->usages_count ?? 0) / $coupon->usage_limit) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-coupon-status" type="checkbox" 
                                               data-id="{{ $coupon->id }}" {{ $coupon->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-soft-info">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" 
                                              onsubmit="return confirm('Delete this coupon?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No coupons found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-coupon-status').forEach(el => {
        el.addEventListener('change', function() {
            const id = this.dataset.id;
            fetch(`/coupons/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res => res.json())
            .then(data => {
                if(!data.success) {
                    this.checked = !this.checked;
                    alert('Failed to update status.');
                }
            });
        });
    });
</script>
@endpush
