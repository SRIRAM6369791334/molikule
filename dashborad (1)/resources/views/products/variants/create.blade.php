@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Variant to {{ $product->name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.variants.index', ['product' => $product]) }}">{{ $product->name }}</a></li>
                    <li class="breadcrumb-item active">Add Variant</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Product Variant</h4>
                <p class="card-title-desc">Add a variant option for this product (e.g., different size, flavour, scent)</p>
            </div>
            <div class="card-body">
                <form action="{{ route('products.variants.store', ['product' => $product]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                    <!-- Product Info Summary -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ $product->image_url ?: asset('assets/images/product/img-1.png') }}"
                                     alt="{{ $product->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <p class="mb-1">Base Price: <span class="text-success fw-bold">₹{{ number_format($product->price, 2) }}</span></p>
                                <p class="mb-0">Stock: <span class="badge bg-primary">{{ $product->stock_quantity }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="variant_name" class="form-label">Variant Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('variant_name') is-invalid @enderror"
                                       id="variant_name" name="variant_name"
                                       list="nameList"
                                       value="{{ old('variant_name') }}" required
                                       placeholder="e.g., Lemon, Rose, 1 Litre">
                                <datalist id="nameList">
                                    @foreach($existingNames ?? [] as $name)
                                        <option value="{{ $name }}">
                                    @endforeach
                                </datalist>
                                @error('variant_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Descriptive name for this variant option</small> 
                                @if($existingNames->count() > 0)
                                    <br><small class="text-info">Suggestions available from existing variants</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="variant_type" class="form-label">Variant Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('variant_type') is-invalid @enderror"
                                       id="variant_type" name="variant_type"
                                       list="typeList"
                                       value="{{ old('variant_type') }}" required
                                       placeholder="e.g., size, color, flavour">
                                <datalist id="typeList">
                                    @foreach($existingTypes ?? [] as $type)
                                        <option value="{{ $type }}">
                                    @endforeach
                                    <option value="size">
                                    <option value="color">
                                    <option value="scent">
                                    <option value="flavour">
                                    <option value="volume">
                                </datalist>
                                @error('variant_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">How these variants differ (e.g., size, flavour)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Variant Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') ?? $product->price }}"
                                           placeholder="Leave empty to use base price">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Price different from base product price</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity"
                                       value="{{ old('stock_quantity') }}" required min="0">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">How many of this variant are in stock</small>
                            </div>
                        </div>
                    </div>

                    <!-- Sort Order field removed -->

                    <!-- Special Fields for Different Types -->
                    <div id="colorFields" class="row mb-3" style="display: none;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="variant_color_code" class="form-label">Color Code</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color @error('variant_color_code') is-invalid @enderror"
                                           id="variant_color_code" name="variant_color_code"
                                           value="{{ old('variant_color_code', '#000000') }}">
                                    <input type="text" class="form-control @error('variant_color_code') is-invalid @enderror"
                                           id="colorHex" placeholder="#000000" readonly>
                                    @error('variant_color_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Pick a color for this variant</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="variant_image" class="form-label">Variant Image</label>
                                <input type="file" class="form-control @error('variant_image') is-invalid @enderror"
                                       id="variant_image" name="variant_image" accept="image/*">
                                @error('variant_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload a specific image for this variant (optional)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror"
                                           type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (available for sale)
                                    </label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="variant_description" class="form-label">Variant Description</label>
                        <x-ckeditor
                            name="variant_description"
                            id="variant_description"
                            :value="old('variant_description')"
                            rows="3"
                            placeholder="Additional details about this specific variant..."
                        />
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-end">
                                <a href="{{ route('products.variants.index', ['product' => $product]) }}" class="btn btn-secondary me-2">
                                    <i class="bx bx-arrow-back me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save me-1"></i>Create Variant
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Preview Card -->
        <div class="card" id="variantPreview" style="display: none;">
            <div class="card-header">
                <h5 class="mb-0">Variant Preview</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div id="previewImage" class="bg-light rounded d-inline-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="bx bx-package text-muted" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h6 id="previewName" class="text-center">Variant Name</h6>
                <p id="previewType" class="text-center mb-2">
                    <span class="badge bg-info">Type</span>
                </p>
                <div class="mb-2">
                    <small class="text-muted">Price:</small>
                    <span id="previewPrice" class="fw-bold text-success">₹0.00</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Stock:</small>
                    <span id="previewStock" class="badge bg-success">0</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Status:</small>
                    <span id="previewStatus" class="badge bg-success">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let basePrice = {{ $product->price }};
    let previewVisible = false;

    // Variant type change handler
    $('#variant_type').on('change', function() {
        const variantType = $(this).val();
        const colorFields = $('#colorFields');

        if (variantType === 'color') {
            colorFields.slideDown();
        } else {
            colorFields.slideUp();
        }
    });

    // Color picker handler
    $('#variant_color_code').on('input', function() {
        $('#colorHex').val($(this).val());
    });

    // Update color hex input when color picker changes
    $('#variant_color_code').on('change', function() {
        $('#colorHex').val($(this).val());
    });

    // Initial color hex value
    $('#colorHex').val($('#variant_color_code').val());

    // Live preview updates
    function updatePreview() {
        const variantName = $('#variant_name').val().trim();
        const variantTypeText = $('#variant_type option:selected').text();
        const price = parseFloat($('#price').val()) || basePrice;
        const stock = parseInt($('#stock_quantity').val()) || 0;
        const isActive = $('#is_active').is(':checked');

        // Show preview only if we have some data
        if (variantName && variantTypeText !== 'Select Type') {
            if (!previewVisible) {
                previewVisible = true;
                $('#variantPreview').fadeIn();
            }

            $('#previewName').text(variantName);
            $('#previewType').text(variantTypeText);
            $('#previewPrice').text('₹' + price.toFixed(2));
            $('#previewStock').text(stock).attr('class', `badge bg-${stock > 10 ? 'success' : (stock > 0 ? 'warning' : 'danger')}`);
            $('#previewStatus').text(isActive ? 'Active' : 'Inactive')
                             .attr('class', `badge bg-${isActive ? 'success' : 'danger'}`);
        } else {
            if (previewVisible) {
                previewVisible = false;
                $('#variantPreview').fadeOut();
            }
        }
    }

    // Update preview on input changes
    $('#variant_name, #variant_type, #price, #stock_quantity').on('input change', updatePreview);
    $('#is_active').on('change', updatePreview);

    // Initialize preview
    updatePreview();


    // Image preview
    $('#variant_image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').html(`<img src="${e.target.result}" class="w-100 h-100 object-cover rounded" alt="Preview">`);
            };
            reader.readAsDataURL(file);
        }
    });

    // Initialize variant type display
    $('#variant_type').trigger('change');
});
</script>
@endpush
