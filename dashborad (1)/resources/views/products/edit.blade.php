@extends('layouts.app')
@section('title') Edit Product | {{ $product->name }} @endsection

@section('css')
    {{-- Select2 CSS --}}
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .premium-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        }

        .section-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(85, 110, 230, 0.1);
            color: #556ee6;
            font-size: 18px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Stepper UI */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .stepper-wrapper::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            width: 100%;
            background: #e9ecef;
            z-index: 1;
            transform: translateY(-50%);
        }

        .stepper-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
            padding: 0 10px;
        }

        .step-counter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #74788d;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .stepper-item.active .step-counter {
            background: #556ee6;
            color: #fff;
            border-color: #556ee6;
        }

        .stepper-item.completed .step-counter {
            background: #34c38f;
            color: #fff;
            border-color: #34c38f;
        }

        .step-name {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 8px;
            color: #74788d;
        }

        .stepper-item.active .step-name {
            color: #556ee6;
        }

        .stepper-item.completed .step-name {
            color: #34c38f;
        }

        .wizard-step {
            display: none;
        }

        .wizard-step.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .required::after {
            content: " *";
            color: #f46a6a;
        }

        .dark-asterisk {
            color: #f46a6a;
            font-weight: bold;
            margin-left: 4px;
        }

        .just-validate-error-label {
            color: #f46a6a !important;
            font-size: 12px !important;
            margin-top: 4px !important;
            display: block !important;
            font-weight: 500;
        }

        .variant-box {
            background: #f8f9fa;
            border: 1px dashed #34c38f;
            border-radius: 12px;
            padding: 24px;
        }

        .image-preview-wrapper {
            width: 100%;
            height: 200px;
            border: 2px dashed #ced4da;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: #f9f9f9;
        }

        #image-preview {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .gallery-item {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Product Master</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Inventory</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-block-helper me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="productForm" action="{{ route('products.update', $product->slug ?: $product->product_id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            {{-- Wizard Stepper --}}
            <div class="col-lg-10 offset-lg-1 mb-4">
                <div class="card premium-card">
                    <div class="card-body p-4">
                        <div class="stepper-wrapper">
                            <div class="stepper-item active" id="step-1-indicator">
                                <div class="step-counter">1</div>
                                <div class="step-name">General</div>
                            </div>
                            <div class="stepper-item" id="step-2-indicator">
                                <div class="step-counter">2</div>
                                <div class="step-name">Inventory</div>
                            </div>
                            <div class="stepper-item" id="step-3-indicator">
                                <div class="step-counter">3</div>
                                <div class="step-name">Logistics</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Column --}}
            <div class="col-lg-10 offset-lg-1">
                {{-- 1. General Details --}}
                <div class="wizard-step active" id="step-1">
                    <div class="card premium-card mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <span class="section-icon me-3"><i class="bx bx-info-circle"></i></span>
                                1. General Product Details
                            </h5>

                            <div class="mb-4">
                                <label class="form-label required" for="name">Product Name</label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $product->name) }}"
                                    placeholder="e.g. Molikule Premium Floor Cleaner" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Product Image <span
                                        class="dark-asterisk">*(1080x1080)</span></label>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" name="image" id="product_image"
                                            accept="image/*" onchange="previewMainImage(this)">
                                    </div>
                                    <div class="col-md-4">
                                        <div id="image-preview-container" class="border rounded bg-light overflow-hidden"
                                            style="height: 100px;">
                                            <img id="image-preview" src="{{ $product->image }}"
                                                class="img-fluid h-100 w-100" style="object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Current image will be kept if you don't upload a new one.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label required">Category</label>
                                        <select class="form-control select2" name="category_id" id="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label required">Brand</label>
                                        <select class="form-control select2" name="brand_id" id="brand_id" required>
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->brand_id }}" {{ old('brand_id', $product->brand_id) == $brand->brand_id ? 'selected' : '' }}>
                                                    {{ $brand->brand_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Short Summary</label>
                                <input type="text" id="short_description" name="short_description" class="form-control"
                                    value="{{ old('short_description', $product->short_description) }}"
                                    placeholder="Brief highlight line for list views" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Detailed Specification (Description)</label>
                                <textarea id="description" name="description" rows="4" class="form-control"
                                    placeholder="Explain product benefits, usage, and key features..."
                                    required>{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep(2)">Next:
                                    Inventory <i class="bx bx-right-arrow-alt ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden sections --}}
                <div class="d-none">
                    <input type="file" name="gallery_images[]" multiple>
                </div>

                {{-- 2. Inventory Step --}}
                <div class="wizard-step" id="step-2">
                    <div class="card premium-card mb-4 border-success" style="border: 1px solid rgba(52, 195, 143, 0.3);">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center text-success">
                                <span class="section-icon bg-soft-success text-success me-3"><i
                                        class="bx bx-package"></i></span>
                                2. Base Inventory & Pricing
                            </h5>

                            @php
                                $firstVariant = $product->variants->first();
                            @endphp

                            <div class="variant-box">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Flavour / Scent</label>
                                        <input type="text" class="form-control" id="initial_flavour" name="variant_value"
                                            value="{{ $firstVariant->value ?? '' }}" placeholder="e.g. Lemon" required
                                            oninput="syncPreviewName()">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Size</label>
                                        <select class="form-select select2" id="initial_unit" name="variant_unit" required
                                            onchange="syncPreviewName()">
                                            <option value="">Select Size</option>
                                            @foreach(['50ml', '100ml', '200ml', '250ml', '500ml', '1L', '2L', '5L'] as $size)
                                                <option value="{{ $size }}" {{ ($firstVariant->variant_unit ?? '') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Retail Price (MRP)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" class="form-control" name="variant_mrp"
                                                value="{{ $firstVariant->mrp_price ?? '' }}" required placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Discount Type</label>
                                        <select class="form-select" name="variant_discount_type">
                                            <option value="">No Discount</option>
                                            <option value="percentage" {{ ($firstVariant->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                            <option value="flat" {{ ($firstVariant->discount_type ?? '') == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Discount Value</label>
                                        <input type="number" step="0.01" class="form-control" name="variant_discount_value"
                                            value="{{ $firstVariant->discount_value ?? 0 }}" placeholder="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Stock</label>
                                        <input type="number" class="form-control" name="variant_stock"
                                            value="{{ $firstVariant->stock_quantity ?? 0 }}" required
                                            placeholder="Enter quantity">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Variant SKU</label>
                                        <input type="text" class="form-control" name="variant_sku"
                                            value="{{ $firstVariant->sku ?? '' }}" placeholder="Unique SKU" required>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">Primary Variant Preview:</small>
                                            <h6 class="mb-0 text-primary" id="variant_preview_title">
                                                {{ $firstVariant->variant_name ?? 'Enter details...' }}
                                            </h6>
                                        </div>
                                    </div>
                                    @if($firstVariant)
                                        <input type="hidden" name="variant_id" value="{{ $firstVariant->id }}">
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded border border-info">
                                <p class="text-info mb-0 small"><i class="bx bx-info-circle me-1"></i> Note: This edits the
                                    primary variant. Other variants can be managed in the Variants section.</p>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-light btn-lg px-4" onclick="prevStep(1)"><i
                                        class="bx bx-left-arrow-alt me-1"></i> Back</button>
                                <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep(3)">Next:
                                    Logistics <i class="bx bx-right-arrow-alt ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Logistics & Advanced Step --}}
                <div class="wizard-step" id="step-3">
                    <div class="card premium-card mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <span class="section-icon me-3"><i class="bx bx-cog"></i></span>
                                3. Shipping
                            </h5>

                            <hr class="my-4">
                            <h6 class="mb-3 text-muted">Shipping Dimensions</h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small required">Weight</label>
                                    <input type="number" step="0.01" id="weight" name="weight" class="form-control"
                                        value="{{ old('weight', $product->weight) }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small required">Unit</label>
                                    <select class="form-select" id="weight_unit" name="weight_unit" required>
                                        <option value="kg" {{ $product->weight_unit == 'kg' ? 'selected' : '' }}>kilogram (kg)
                                        </option>
                                        <option value="g" {{ $product->weight_unit == 'g' ? 'selected' : '' }}>gram (g)
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small required">Length</label>
                                    <input type="number" step="0.01" id="length" name="length" class="form-control"
                                        value="{{ old('length', $product->length) }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small required">Width</label>
                                    <input type="number" step="0.01" id="width" name="width" class="form-control"
                                        value="{{ old('width', $product->width) }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small required">Height</label>
                                    <input type="number" step="0.01" id="height" name="height" class="form-control"
                                        value="{{ old('height', $product->height) }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small required">Dim Unit</label>
                                    <select class="form-select" id="dimension_unit" name="dimension_unit" required>
                                        <option value="cm" {{ $product->dimension_unit == 'cm' ? 'selected' : '' }}>cm
                                        </option>
                                        <option value="mm" {{ $product->dimension_unit == 'mm' ? 'selected' : '' }}>mm
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-3 text-muted">Product Visibility & Status</h6>
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Publishing Status</label>
                                    <select class="form-select" name="active">
                                        <option value="1" {{ $product->active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$product->active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch form-switch-md">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">Popular Pick</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch form-switch-md">
                                        <input class="form-check-input" type="checkbox" name="is_trending" value="1" {{ $product->is_trending ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">Trending Item</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-light btn-lg px-4" onclick="prevStep(2)"><i
                                        class="bx bx-left-arrow-alt me-1"></i> Back</button>
                                <button type="submit" class="btn btn-success btn-lg px-5 fw-bold" id="submit-btn">
                                    Synchronize Changes <i class="bx bx-check-circle ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden SEO --}}
                <div class="d-none">
                    <input type="text" name="meta_title" value="{{ $product->meta_title }}">
                    <textarea name="meta_description">{{ $product->meta_description }}</textarea>
                    <input type="text" name="meta_keywords" value="{{ $product->meta_keywords }}">
                </div>
            </div>
        </div>

        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
    <script src="{{ asset('assets/js/app/ProductsPage.js') }}?v={{ time() }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });
        });

        function nextStep(step) {
            // Basic validation check
            const currentStep = step - 1;
            let isValid = true;
            $(`#step-${currentStep} [required]`).each(function () {
                if (!$(this).val()) {
                    if (!$(this).is('select')) {
                        $(this).addClass('is-invalid');
                    }
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    title: 'Missing Information',
                    text: 'Please fill in all mandatory fields (marked with *) before proceeding.',
                    icon: 'warning',
                    confirmButtonColor: '#556ee6'
                });
                return;
            }

            $('.wizard-step').removeClass('active');
            $(`#step-${step}`).addClass('active');

            $('.stepper-item').removeClass('active');
            for (let i = 1; i <= step; i++) {
                $(`#step-${i}-indicator`).addClass('active');
                if (i < step) $(`#step-${i}-indicator`).addClass('completed');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function prevStep(step) {
            $('.wizard-step').removeClass('active');
            $(`#step-${step}`).addClass('active');

            $('.stepper-item').removeClass('completed');
            $('.stepper-item').removeClass('active');
            for (let i = 1; i <= step; i++) {
                $(`#step-${i}-indicator`).addClass('active');
                if (i < step) $(`#step-${i}-indicator`).addClass('completed');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }


        function syncPreviewName() {
            const flavor = $('#initial_flavour').val().trim();
            const unit = $('#initial_unit').val();
            const p = $('#variant_preview_title');
            p.text((flavor && unit) ? (flavor + ' \u2013 ' + unit) : (flavor || unit || 'Enter details...'));
        }

        function previewMainImage(input) {
            const container = $('#image-preview-container');
            const preview = $('#image-preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.src = e.target.result;

                    img.onload = function () {
                        if (this.width !== 1080 || this.height !== 1080) {
                            Swal.fire({
                                title: 'Invalid Image Size',
                                html: `Your image is <b>${this.width}x${this.height}</b>. <br>The required size is exactly <b>1080x1080</b> pixels.`,
                                icon: 'error',
                                confirmButtonColor: '#f46a6a'
                            });
                            input.value = '';
                            $(input).addClass('is-invalid');
                        } else {
                            $(input).removeClass('is-invalid').addClass('is-valid');
                            preview.attr('src', e.target.result);
                        }
                    };
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush