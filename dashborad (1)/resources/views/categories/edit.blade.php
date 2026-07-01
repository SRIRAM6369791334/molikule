@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Edit Category</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Edit {{ $category->category_name }}</li>
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
                    <h4 class="card-title">Edit Category Information</h4>
                    <p class="card-title-desc">Update category details and settings</p>
                </div>
                <div class="card-body">

                    <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data"
                        id="editCategoryForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="category_name" class="form-label">Category Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('category_name') is-invalid @enderror"
                                        id="category_name" name="category_name"
                                        value="{{ old('category_name', $category->category_name) }}" required>
                                    @error('category_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                        name="image" accept="image/*"
                                        onchange="ImageValidator.previewImage(this, 'imagePreview', 'previewImg')">
                                    <small class="text-muted">Supported formats: JPEG, PNG, GIF, WebP. Max size: 5MB<br>
                                        <strong>Requirements:</strong> 800x800px<br> <em>Note: Images will be automatically
                                            optimized and converted to WebP format</em></small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <div id="imagePreview" class="mt-2" style="display: none;">
                                            <img id="previewImg" src="#" alt="Category image preview"
                                                class="img-fluid rounded border"
                                                style="max-height: 150px; object-fit: cover;">
                                            <br><small class="text-muted">Image preview</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description field removed -->

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Sort Order field removed -->
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Theme Settings</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="theme_primary_color" class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" id="theme_primary_color" name="theme_primary_color" value="{{ old('theme_primary_color', $category->theme_primary_color ?? '#bbd700') }}" title="Choose primary color">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="theme_light_color" class="form-label">Light Glow Color (rgba)</label>
                                    <input type="text" class="form-control" id="theme_light_color" name="theme_light_color" value="{{ old('theme_light_color', $category->theme_light_color ?? 'rgba(187, 215, 0, 0.3)') }}" placeholder="e.g. rgba(187, 215, 0, 0.3)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="theme_border_radius" class="form-label">Card Shape (Border Radius)</label>
                                    <select class="form-select" id="theme_border_radius" name="theme_border_radius">
                                        <option value="4px" {{ (old('theme_border_radius', $category->theme_border_radius) == '4px') ? 'selected' : '' }}>Sharp (4px)</option>
                                        <option value="20px" {{ (old('theme_border_radius', $category->theme_border_radius) == '20px') ? 'selected' : '' }}>Round (20px)</option>
                                        <option value="40px" {{ (old('theme_border_radius', $category->theme_border_radius) == '40px') ? 'selected' : '' }}>Pill (40px)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="theme_bg_overlay" class="form-label">Background Overlay Tint (rgba)</label>
                                    <input type="text" class="form-control" id="theme_bg_overlay" name="theme_bg_overlay" value="{{ old('theme_bg_overlay', $category->theme_bg_overlay ?? 'rgba(255,255,255,0.95)') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="theme_bg_image" class="form-label">Theme Background Image</label>
                                    <input type="file" class="form-control" id="theme_bg_image" name="theme_bg_image" accept="image/*">
                                    @if($category->getRawOriginal('theme_bg_image'))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $category->getRawOriginal('theme_bg_image')) }}" alt="Theme BG" style="height: 60px; object-fit: cover; border-radius: 4px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (visible to customers)
                                </label>
                            </div>
                        </div>

                        @if($category->getRawOriginal('image'))
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="{{ $category->image_url }}" alt="{{ $category->category_name }}"
                                        style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Back to Categories
                            </a>

                            <div>
                                <button type="submit" class="btn btn-primary" id="editCategoryBtn" id="editCategoryBtn">
                                    <i class="bx bx-save me-1"></i>Update Category
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
                requiredWidth: 800,
                requiredHeight: 800,
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
            const categoryNameField = document.getElementById('category_name');
            if (categoryNameField) {
                // Show validation errors only on blur (when user leaves the field)
                categoryNameField.addEventListener('blur', function () {
                    if (!this.value || this.value.trim() === '') {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            }

            const form = document.getElementById('editCategoryForm');
            const editBtn = document.getElementById('editCategoryBtn');

            form.addEventListener('submit', function (e) {
                // Don't prevent default to allow form submission
                if (editBtn && !editBtn.disabled) {
                    editBtn.disabled = true;
                    editBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating Category...';
                }
            });

            // Reset form state if there are validation errors
            @if($errors->any())
                if (editBtn) {
                    editBtn.disabled = false;
                    editBtn.innerHTML = '<i class="bx bx-save me-1"></i>Update Category';
                }
            @endif
    });
    </script>
@endpush