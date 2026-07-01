@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ $product->name }} Variants</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $product->name }}</a></li>
                    <li class="breadcrumb-item active">Variants</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <!-- Product Info Card -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ $product->image_url ?: asset('assets/images/product/img-1.png') }}"
                             alt="{{ $product->name }}" class="img-fluid rounded">
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-1">{{ $product->name }}</h5>
                        <p class="text-muted mb-2">{{ strip_tags($product->description) }}</p>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <p class="mb-1 text-success">₹{{ number_format($product->price, 2) }}</p>
                                <small class="text-muted">Base Price</small>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1">{{ $product->stock_quantity }}</p>
                                <small class="text-muted">Stock</small>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1">{{ $variants->count() }}</p>
                                <small class="text-muted">Variants</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-{{ $product->active ? 'success' : 'danger' }}">
                                    {{ $product->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('products.variants.create', ['product' => $product]) }}" class="btn btn-success btn-sm">
                            <i class="bx bx-plus me-1"></i>Add Variant
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                            <i class="bx bx-arrow-back me-1"></i>Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variants Management -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Variants</h4>
                <p class="card-title-desc">Manage different options for this product (sizes, colors, etc.)</p>
            </div>
            <div class="card-body">
                        @if($variants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-editable table-nowrap align-middle table-edits">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Image</th>
                                <th class="align-middle">Variant Name</th>
                                <th class="align-middle">Type</th>
                                <th class="align-middle">Price</th>
                                <th class="align-middle">Stock</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($variants as $variant)
                            <tr>
                                <td>
                                    @if($variant->image_url)
                                        <img src="{{ asset($variant->image_url) }}" alt="{{ $variant->variant_name }}"
                                             class="avatar-sm rounded">
                                    @else
                                        <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                            <i class="bx bx-package text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ $variant->variant_name }}</h5>
                                    @if($variant->variant_description)
                                        <small class="text-muted">{{ Str::limit($variant->variant_description, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($variant->variant_type) }}</span>
                                </td>
                                <td class="text-success">
                                    @if($variant->price > 0)
                                        ₹{{ number_format($variant->price, 2) }}
                                        <small class="text-muted d-block">
                                            {{ $variant->price > $product->price ? '+' : '' }}
                                            ₹{{ number_format($variant->price - $product->price, 2) }}
                                        </small>
                                    @else
                                        ₹{{ number_format($product->price, 2) }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $variant->stock_quantity > 10 ? 'success' : ($variant->stock_quantity > 0 ? 'warning' : 'danger') }}">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input variant-status-toggle"
                                               type="checkbox"
                                               data-variant-id="{{ $variant->variant_id }}"
                                               {{ $variant->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.variants.edit', ['product' => $product, 'product_variant' => $variant]) }}"
                                           class="btn btn-outline-success btn-sm"
                                           title="Edit Variant">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           class="btn btn-outline-danger btn-sm delete-variant"
                                           title="Delete Variant"
                                           data-variant-id="{{ $variant->variant_id }}"
                                           data-variant-name="{{ $variant->variant_name }}">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($variants && $variants->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4"
                     style="border-top: 1px solid rgba(222, 226, 230, 0.5); padding-top: 16px; font-family: 'Courier New', monospace;">
                    <div class="text-muted">
                        <small style="font-size: 14px;">
                            <i class="bx bx-info-circle me-1"></i>
                            Showing {{ $variants->count() }} of {{ $variants->total() }} variants ({{ $variants->currentPage() }} of {{ $variants->lastPage() }})
                        </small>
                    </div>
                    <div class="custom-pagination">
                        {{ $variants->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
                @else
                <!-- No Variants -->
                <div class="text-center py-5">
                    <i class="bx bx-package display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">No variants yet</h5>
                    <p class="text-muted mb-4">Add different options for this product like sizes, flavours, or scents.</p>
                    <a href="{{ route('products.variants.create', ['product' => $product]) }}" class="btn btn-success">
                        <i class="bx bx-plus me-1"></i>Create First Variant
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Variant Modal -->
<div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVariantModalLabel">Delete Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the variant "<strong id="delete-variant-name"></strong>"?</p>
                <div class="alert alert-warning">
                    <i class="bx bx-info-circle me-2"></i>
                    This action cannot be undone. The variant will be permanently removed.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-variant">
                    <i class="bx bx-trash me-1"></i>Delete Variant
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let variantToDelete = null;

    // Variant status toggle
    $(document).on('change', '.variant-status-toggle', function() {
        const $toggle = $(this);
        const variantId = $toggle.data('variant-id');
        const isActive = $toggle.is(':checked');

        fetch(`{{ url('/product-variants') }}/${variantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                is_active: isActive,
                _method: 'PUT'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // Revert toggle
                $toggle.prop('checked', !isActive);
                alert('Error: ' + (data.message || 'Status update failed'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $toggle.prop('checked', !isActive);
            alert('Status update failed');
        });
    });

    // Delete variant handler
    $(document).on('click', '.delete-variant', function() {
        const variantId = $(this).data('variant-id');
        const variantName = $(this).data('variant-name');

        $('#delete-variant-name').text(variantName);
        $('#confirm-delete-variant').data('variant-id', variantId);

        $('#deleteVariantModal').modal('show');
    });

    // Confirm delete
    $('#confirm-delete-variant').on('click', function() {
        const variantId = $(this).data('variant-id');

        fetch(`{{ url('/products') }}/{{ $product->product_id }}/variants/${variantId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#deleteVariantModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to delete variant'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the variant');
        });
    });
});
</script>
@endpush
