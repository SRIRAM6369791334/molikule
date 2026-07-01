@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Brand</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('dashboard') }}">{{ config('app.name', 'Molikule') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Edit {{ $brand->brand_name }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Brand Information</h4>
                    <p class="card-title-desc">Update brand details and settings</p>
                </div>
                <div class="card-body">

                    <form action="{{ route('brands.update', $brand) }}" method="POST" enctype="multipart/form-data"
                        id="editBrandForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="brand_name" class="form-label">Brand Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('brand_name') is-invalid @enderror"
                                        id="brand_name" name="brand_name"
                                        value="{{ old('brand_name', $brand->brand_name) }}" required>
                                    @error('brand_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Brand Logo <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                        name="logo" accept="image/*"
                                        onchange="ImageValidator.previewImage(this, 'imagePreview', 'previewImg')" {{ !$brand->logo_url ? 'required' : '' }}>
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        {{ $brand->logo_url ? 'Leave empty to keep current logo' : 'Brand logo is required' }}<br>
                                        Supported formats: JPG, PNG, GIF, WebP. Max size: 5MB<br>
                                        <strong>Requirements:</strong> 1024x1024px<br>
                                        <em>Note: Images will be automatically optimized and converted to WebP format</em>
                                    </small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <label class="form-label">New Logo Preview:</label>
                                    <div class="border rounded p-2">
                                        <img id="previewImg" src="" alt="New logo preview" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch form-switch-md mb-3">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active Status</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch form-switch-md mb-3">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Featured Brand</label>
                                </div>
                            </div>
                        </div>

                        @if($brand->logo_url)
                            <div class="mb-3">
                                <label class="form-label">Current Logo</label>
                                <div>
                                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->brand_name }}"
                                        style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Back to Brands
                            </a>

                            <div>
                                <button type="submit" class="btn btn-primary" id="editBrandBtn">
                                    <i class="bx bx-save me-1"></i>Update Brand
                                </button>
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
        const ImageValidator = {
            config: {
                requiredWidth: 1024,
                requiredHeight: 1024,
                maxSize: 5 * 1024 * 1024, // 5MB
                allowedFormats: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
            },

            validateImageFile(file) {
                const errors = [];
                if (!file) return { valid: false, errors: ['No file selected'] };
                if (!this.config.allowedFormats.includes(file.type)) {
                    errors.push(`Invalid format. Allowed: JPEG, PNG, GIF, WebP`);
                }
                if (file.size > this.config.maxSize) {
                    const maxSizeMB = this.config.maxSize / (1024 * 1024);
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    errors.push(`File size (${fileSizeMB}MB) exceeds max (${maxSizeMB}MB)`);
                }
                return { valid: errors.length === 0, errors };
            },

            validateImageDimensions(img) {
                const errors = [];
                if (img.width !== this.config.requiredWidth) {
                    errors.push(`Width must be ${this.config.requiredWidth}px (current: ${img.width}px)`);
                }
                if (img.height !== this.config.requiredHeight) {
                    errors.push(`Height must be ${this.config.requiredHeight}px (current: ${img.height}px)`);
                }
                return { valid: errors.length === 0, errors };
            },

            showError(input, feedbackDiv, errors) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                feedbackDiv.innerHTML = errors.join('<br>');
                feedbackDiv.style.display = 'block';
            },

            clearError(input, feedbackDiv) {
                input.classList.remove('is-invalid');
                if (feedbackDiv) {
                    feedbackDiv.style.display = 'none';
                    feedbackDiv.innerHTML = '';
                }
            },

            createFeedbackDiv(input) {
                let feedbackDiv = input.nextElementSibling;
                if (!feedbackDiv || !feedbackDiv.classList.contains('invalid-feedback')) {
                    feedbackDiv = document.createElement('div');
                    feedbackDiv.className = 'invalid-feedback d-block';
                    input.parentNode.insertBefore(feedbackDiv, input.nextSibling);
                }
                return feedbackDiv;
            },

            previewImage(input, previewId, previewImgId) {
                const preview = document.getElementById(previewId);
                const previewImg = document.getElementById(previewImgId);
                const feedbackDiv = this.createFeedbackDiv(input);

                if (!input.files || !input.files[0]) {
                    preview.style.display = 'none';
                    this.clearError(input, feedbackDiv);
                    return;
                }

                const file = input.files[0];
                const fileValidation = this.validateImageFile(file);

                if (!fileValidation.valid) {
                    this.showError(input, feedbackDiv, fileValidation.errors);
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        const dimensionValidation = this.validateImageDimensions(img);
                        if (!dimensionValidation.valid) {
                            this.showError(input, feedbackDiv, dimensionValidation.errors);
                            input.value = '';
                            preview.style.display = 'none';
                            return;
                        }
                        this.clearError(input, feedbackDiv);
                        previewImg.src = e.target.result;
                        preview.style.display = 'block';
                        input.classList.add('is-valid');
                    };
                    img.onerror = () => {
                        this.showError(input, feedbackDiv, ['Error reading image']);
                        input.value = '';
                        preview.style.display = 'none';
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Blur-only validation for required fields
            const brandNameField = document.getElementById('brand_name');
            if (brandNameField) {
                // Show validation errors only on blur (when user leaves the field)
                brandNameField.addEventListener('blur', function () {
                    if (!this.value || this.value.trim() === '') {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            }

            const form = document.getElementById('editBrandForm');
            const editBtn = document.getElementById('editBrandBtn');

            form.addEventListener('submit', function (e) {
                // Don't prevent default to allow form submission
                if (editBtn && !editBtn.disabled) {
                    editBtn.disabled = true;
                    editBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating Brand...';
                }
            });

            // Reset form state if there are validation errors
            @if($errors->any())
                if (editBtn) {
                    editBtn.disabled = false;
                    editBtn.innerHTML = '<i class="bx bx-save me-1"></i>Update Brand';
                }
            @endif
    });
    </script>
@endpush