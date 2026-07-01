@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Product Variant Details</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('product-variants.index') }}">Product Variants</a></li>
                    <li class="breadcrumb-item active">{{ $variant->variant_name }}</li>
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
    <div class="col-lg-8">
        <!-- Variant Details Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $variant->variant_name }}</h5>
                    <div class="d-flex gap-2">
                       
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('product-variants.edit', ['product_variant' => $variant]) }}">
                                        <i class="bx bx-edit me-2"></i>Edit Variant
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                       onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this variant?')) { document.getElementById('delete-form').submit(); }">
                                        <i class="bx bx-trash me-2"></i>Delete Variant
                                    </a>
                                </li>
                            </ul>
                            <form id="delete-form" action="{{ route('product-variants.destroy', ['product_variant' => $variant]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Main Image -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="text-center">
                            @if($variant->image_url)
                                <img src="{{ $variant->image_url }}" alt="{{ $variant->variant_name }}"
                                     class="img-fluid rounded shadow" style="max-height: 400px; object-fit: contain;">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" alt="{{ $variant->variant_name }}"
                                     class="img-fluid rounded shadow" style="max-height: 400px; object-fit: contain;">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Variant Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold mb-3">Basic Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>Variant ID:</strong></td>
                                <td>#{{ $variant->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Flavour:</strong></td>
                                <td>
                                    @if($variant->value)
                                        <span class="badge bg-success-subtle text-success">{{ $variant->value }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Volume:</strong></td>
                                <td>
                                    @if($variant->variant_unit)
                                        <span class="badge bg-primary-subtle text-primary">{{ $variant->variant_unit }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $variant->active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $variant->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="font-weight-bold mb-3">Pricing & Stock</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>MRP Price:</strong></td>
                                <td class="text-success h6 mb-0">
                                    @if($variant->mrp_price)
                                        &#8377;{{ number_format($variant->mrp_price, 2) }}
                                    @elseif($variant->product)
                                        &#8377;{{ number_format($variant->product->mrp_price, 2) }}
                                        <small class="text-muted">(from product)</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @if($variant->discount_type)
                            <tr>
                                <td><strong>Discount:</strong></td>
                                <td>
                                    {{ $variant->discount_type == 'percentage' ? $variant->discount_value.'%' : '&#8377;'.$variant->discount_value }}
                                    off
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Stock:</strong></td>
                                <td>
                                    @php $qty = $variant->stock_quantity; @endphp
                                    @if($qty <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($qty <= ($variant->low_stock ?? 5))
                                        <span class="badge bg-warning text-dark">Low Stock ({{ $qty }})</span>
                                    @else
                                        <span class="badge bg-success">{{ $qty }} in stock</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Low Stock Alert:</strong></td>
                                <td>{{ $variant->low_stock ?? 5 }} units</td>
                            </tr>
                        </table>
                    </div>
                </div>


            </div>
        </div>

        <!-- Image Information -->
      
    </div>

    <!-- Sidebar Information -->
    <div class="col-lg-4">
        @if($variant->product)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Related Product</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($variant->product->image_url)
                    <img src="{{ $variant->product->image_url }}" alt="{{ $variant->product->name }}"
                         class="avatar-sm me-3 rounded">
                    @endif
                    <div class="flex-1" >
                        <h6 class="mb-1">{{ $variant->product->name }}</h6>
                        <small class="text-muted">Product ID: #{{ $variant->product->product_id }}</small>
                    </div>
                </div>


                @if($variant->product->mrp_price > 0)
                <p class="text-muted mb-3"><strong>Base Price:</strong> &#8377;{{ number_format($variant->product->mrp_price, 2) }}</p>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('products.show', $variant->product) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="bx bx-show me-1"></i>View Product
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const variantId = this.dataset.variantId;
            const isActive = this.checked;
            const statusText = this.parentElement.querySelector('.form-check-label');

            statusText.textContent = isActive ? 'Active' : 'Inactive';

            fetch(`{{ url('product-variants') }}/${variantId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ is_active: isActive })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    this.checked = !isActive;
                    statusText.textContent = !isActive ? 'Active' : 'Inactive';
                    showToast('error', data.message || 'Failed to update status');
                }
            })
            .catch(() => {
                this.checked = !isActive;
                statusText.textContent = !isActive ? 'Active' : 'Inactive';
                showToast('error', 'Network error. Status not changed.');
            });
        });
    });
});

function showDeleteModal() {
    if (confirm('Are you sure you want to delete this variant? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}

function showToast(type, message) {
    const toastContainer = document.createElement('div');
    toastContainer.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show custom-toast position-fixed`;
    toastContainer.style.cssText = `
        top: 20px; right: 20px; z-index: 9999; min-width: 350px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    toastContainer.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2 fs-5"></i>
            <div class="me-auto">${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.body.appendChild(toastContainer);
    setTimeout(() => toastContainer.remove(), 3000);
}
</script>
@endpush
