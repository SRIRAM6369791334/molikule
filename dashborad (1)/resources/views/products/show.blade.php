@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ $product->name }}</h4>

            <div class="page-title-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bx bx-arrow-back me-1"></i>Back to Products
                </a>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-4">
                        <div class="text-center">
                            <img src="{{ $product->image_url ?: asset('assets/images/product/img-1.png') }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-8">
                        <h4 class="text-primary">{{ $product->name }}</h4>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Product ID:</strong></td>
                                        <td>{{ $product->product_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category:</strong></td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-info">{{ $product->category->category_name }}</span>
                                            @else
                                                <span class="text-muted">No Category</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Brand:</strong></td>
                                        <td>
                                            @if($product->brand)
                                                <span class="badge bg-primary">{{ $product->brand->brand_name }}</span>
                                            @else
                                                <span class="text-muted">No Brand</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Price:</strong></td>
                                        <td class="text-success font-size-16">
                                            <strong>{!! $product->formatted_price !!}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock Status:</strong></td>
                                        <td>{!! $product->stock_badge !!}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>{!! $product->status_badge !!}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @if($product->part_number)
                                    <tr>
                                        <td><strong>Part Number:</strong></td>
                                        <td>{{ $product->part_number }}</td>
                                    </tr>
                                    @endif
                                    @if($product->made_in)
                                    <tr>
                                        <td><strong>Made In:</strong></td>
                                        <td>{{ $product->made_in }}</td>
                                    </tr>
                                    @endif
                                    @if($product->weight)
                                    <tr>
                                        <td><strong>Weight:</strong></td>
                                        <td>{{ $product->weight }}</td>
                                    </tr>
                                    @endif
                                    @if($product->dimension)
                                    <tr>
                                        <td><strong>Dimension:</strong></td>
                                        <td>{{ $product->dimension }}</td>
                                    </tr>
                                    @endif
                                    @if($product->condition)
                                    <tr>
                                        <td><strong>Condition:</strong></td>
                                        <td>{{ $product->condition }}</td>
                                    </tr>
                                    @endif
                                    @if($product->warranty)
                                    <tr>
                                        <td><strong>Warranty:</strong></td>
                                        <td>{{ $product->warranty }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($product->description)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h5 class="text-primary">Description</h5>
                        <p class="text-muted">{!! nl2br(e($product->description)) !!}</p>
                    </div>
                </div>
                @endif

                @if($product->supported_brands)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h5 class="text-primary">Supported Brands</h5>
                        <p class="text-muted">{{ $product->supported_brands }}</p>
                    </div>
                </div>
                @endif

                @if($product->tags)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h5 class="text-primary">Tags</h5>
                        <p class="text-muted">{{ $product->tags }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Panel -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.edit', $product->product_id) }}"
                       class="btn btn-success">
                        <i class="bx bx-edit me-1"></i>Edit Product
                    </a>


                    <!-- Status Toggle -->
                    <form id="statusForm" method="POST" action="{{ route('products.toggle-status', $product->product_id) }}"
                          style="display: inline;">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="active" value="{{ $product->active ? '0' : '1' }}">
                        @if($product->active)
                            <button type="button" class="btn btn-outline-secondary w-100"
                                    onclick="confirmStatusChange(this, 'deactivate')">
                                <i class="bx bx-pause-circle me-1"></i>Deactivate Product
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-success w-100"
                                    onclick="confirmStatusChange(this, 'activate')">
                                <i class="bx bx-play-circle me-1"></i>Activate Product
                            </button>
                        @endif
                    </form>

                    @if($product->stock_quantity < 20)
                    <div class="alert alert-warning mt-2" role="alert">
                        <i class="bx bx-bell me-1"></i>
                        Low stock alert! Only {{ $product->stock_quantity }} remaining.
                    </div>
                    @elseif($product->stock_quantity == 0)
                    <div class="alert alert-danger mt-2" role="alert">
                        <i class="bx bx-x-circle me-1"></i>
                        This product is out of stock.
                    </div>
                    @endif

                    <!-- Delete Button -->
                    <button type="button" class="btn btn-outline-danger"
                            onclick="confirmDelete({{ $product->product_id }}, '{{ addslashes($product->name) }}')"
                            id="delete-btn">
                        <i class="bx bx-trash me-1"></i>Delete Product
                    </button>
                </div>
            </div>
        </div>

        <!-- Variants Section -->
        @if($product->variants->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Variants ({{ $product->variants->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($product->variants as $variant)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $variant->variant_name }}</strong>
                            <div class="text-muted small">
                                Price: {!! $variant->formatted_price ?? '$' . number_format($variant->price, 2) !!}
                                | Stock: {!! $variant->stock_badge ?? '<span class="badge bg-secondary">' . $variant->stock_quantity . '</span>' !!}
                            </div>
                        </div>
                        <div>
                            @if($variant && $variant->id)
                            <a href="{{ route('product-variants.show', $variant) }}"
                               class="btn btn-sm btn-outline-primary">View</a>
                            @else
                            <button class="btn btn-sm btn-outline-secondary disabled" disabled>View</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="card mt-3">
            <div class="card-body text-center">
                <i class="bx bx-package display-4 text-muted"></i>
                <h5 class="mt-3">No Variants</h5>
                <p class="text-muted">This product has no variants configured.</p>
                <a href="{{ route('products.variants.create', $product->product_id) }}" class="btn btn-primary">Add Variant</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>

function confirmStatusChange(button, action) {
    const productName = '{{ addslashes($product->name) }}';
    const actionText = action === 'activate' ? 'activate' : 'deactivate';

    if (confirm(`Are you sure you want to ${actionText} "${productName}"?`)) {
        button.closest('form').submit();
    }
}

function confirmDelete(productId, productName) {
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        // Disable button and show loading state
        const deleteBtn = document.getElementById('delete-btn');
        const originalHtml = deleteBtn.innerHTML;
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';

        fetch(`{{ url('/products') }}/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 400) {
                    // Business logic error (e.g., product has orders)
                    return response.json().then(data => {
                        throw new Error(data.message || 'Cannot delete this product');
                    });
                }
                if (response.status === 403) {
                    throw new Error('You do not have permission to delete this product');
                }
                if (response.status === 419) {
                    alert('CSRF token expired. Please refresh the page and try again.');
                    return;
                }
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message and redirect
                showToast('success', 'Product deleted successfully!');
                setTimeout(() => {
                    window.location.href = '{{ route("products.index") }}';
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to delete product');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);

            // Reset button state
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalHtml;

            // Show appropriate error message
            const errorMsg = error.message.includes('HTTP') ?
                'Server error occurred. Please try again.' :
                error.message;

            // Check if products table exists on current page, redirect to index
            if (error.message.includes('Cannot delete') || error.message.includes('has orders')) {
                showToast('error', errorMsg);
            } else {
                alert('Error: ' + errorMsg);
            }
        });
    }
}

// Enhanced toast notification system
function showToast(type, message) {
    // Create toast element
    const toastHtml = `
        <div class="toast toast-${type}" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px;">
            <div class="toast-body d-flex align-items-center">
                <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2 fs-5"></i>
                <div class="me-auto">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    // Add to DOM
    const toastContainer = document.createElement('div');
    toastContainer.innerHTML = toastHtml;
    document.body.appendChild(toastContainer);

    const toastElement = toastContainer.querySelector('.toast');

    // Initialize Bootstrap toast
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Remove after showing
        toastElement.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(toastContainer);
        });
    } else {
        // Fallback if Bootstrap not available
        setTimeout(() => {
            if (document.body.contains(toastContainer)) {
                document.body.removeChild(toastContainer);
            }
        }, 3000);
    }
}
</script>
@endpush
