@extends('layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Edit Banner</h4>
                    </div>
                    <a href="{{ route('banners.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Banners
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading mb-2">Validation Errors</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('banners.update', $banner) }}" enctype="multipart/form-data" id="createBannerForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-8" style="display:none">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $banner->title) }}" maxlength="50"
                                               placeholder="e.g., Best Floor Care Solutions">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Max 50 characters. Makes your banner more engaging.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="subtitle" class="form-label">Subtitle</label>
                                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                               id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" maxlength="150"
                                               placeholder="e.g., Limited Time Offer" title="Add a catchy subtitle for your banner">
                                        @error('subtitle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Max 150 characters. Makes your banner more engaging.</div>
                                    </div>



                                    <div class="row">
                                        <input type="hidden" id="position" name="position" value="{{ old('position', $banner->position) }}">
                                        <input type="hidden" id="banner_type" name="banner_type" value="{{ old('banner_type', $banner->banner_type) }}">
                                        <div class="col-md-6">
                                            <!-- Sort Order field removed -->
                                        </div>
                                    </div>

                                    <div class="mb-3" style="display: none;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                   {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Banner Image -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Banner Image</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*" onchange="BannerImageValidator.previewImage(this)">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Supported formats: JPEG, PNG, GIF, WebP. Max size: 5MB <br/> <strong>Required dimensions: 1521x580</strong></div>
                                    </div>

                                    @if($banner->image_url)
                                        <div class="mb-3">
                                            <label class="form-label">Current Image:</label>
                                            <div class="border rounded p-2">
                                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                                     class="img-fluid rounded">
                                            </div>
                                        </div>
                                    @endif

                                    <div id="imagePreview" class="mb-3" style="display: none;">
                                        <label class="form-label">Image Preview:</label>
                                        <div class="border rounded p-2">
                                            <img id="previewImg" src="" alt="Banner image preview" class="img-fluid rounded" title="Your selected banner image">
                                        </div>
                                    </div>

                                    <!-- <div class="mb-3">
                                        <label for="minimage" class="form-label">Upload Min Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('minimage') is-invalid @enderror"
                                               id="minimage" name="minimage" accept="image/*" onchange="BannerImageValidator.previewMinImage(this)">
                                        @error('minimage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Supported formats: JPEG, PNG, GIF, WebP. Max size: 5MB <br/> <strong>Required dimensions: 1024x1024</strong></div>
                                    </div>

                                    @if($banner->minimage_url)
                                        <div class="mb-3">
                                            <label class="form-label">Current Min Image:</label>
                                            <div class="border rounded p-2">
                                                <img src="{{ $banner->minimage_url }}" alt="Min image"
                                                     class="img-fluid rounded">
                                            </div>
                                        </div>
                                    @endif

                                    <div id="minImagePreview" class="mb-3" style="display: none;">
                                        <label class="form-label">Min Image Preview:</label>
                                        <div class="border rounded p-2">
                                            <img id="previewMinImg" src="" alt="Banner minimum size image preview" class="img-fluid rounded" title="Your selected minimum size image">
                                        </div>
                                    </div> -->
                                </div>
                            </div>

                            <input type="hidden" id="button_text" name="button_text" value="{{ old('button_text', $banner->button_text) }}">
                            <input type="hidden" id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}">
                            <input type="hidden" id="link_text" name="link_text" value="{{ old('link_text', $banner->link_text) }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="createBannerBtn">
                            <i class="bx bx-save me-1"></i>Update Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Banner Image Validation
const BannerImageValidator = {
    config: {
        image: {
            requiredWidth: 1521,
            requiredHeight: 580,
            maxSize: 5 * 1024 * 1024, // 5MB
        },
      
        allowedFormats: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
    },

    validateImageFile(file, config) {
        const errors = [];
        if (!file) return { valid: false, errors: ['No file selected'] };
        if (!this.config.allowedFormats.includes(file.type)) {
            errors.push(`Invalid format. Allowed: JPEG, PNG, GIF, WebP`);
        }
        if (file.size > config.maxSize) {
            const maxSizeMB = config.maxSize / (1024 * 1024);
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            errors.push(`File size (${fileSizeMB}MB) exceeds max (${maxSizeMB}MB)`);
        }
        return { valid: errors.length === 0, errors };
    },

    validateImageDimensions(img, config) {
        const errors = [];
        if (img.width !== config.requiredWidth) {
            errors.push(`Width must be ${config.requiredWidth}px (current: ${img.width}px)`);
        }
        if (img.height !== config.requiredHeight) {
            errors.push(`Height must be ${config.requiredHeight}px (current: ${img.height}px)`);
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

    previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const feedbackDiv = this.createFeedbackDiv(input);

        if (!input.files || !input.files[0]) {
            preview.style.display = 'none';
            this.clearError(input, feedbackDiv);
            return;
        }

        const file = input.files[0];
        const fileValidation = this.validateImageFile(file, this.config.image);
        
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
                const dimensionValidation = this.validateImageDimensions(img, this.config.image);
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
    },

    previewMinImage(input) {
        const preview = document.getElementById('minImagePreview');
        const previewImg = document.getElementById('previewMinImg');
        const feedbackDiv = this.createFeedbackDiv(input);

        if (!input.files || !input.files[0]) {
            preview.style.display = 'none';
            this.clearError(input, feedbackDiv);
            return;
        }

        const file = input.files[0];
        const fileValidation = this.validateImageFile(file, this.config.minimage);
        
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
                const dimensionValidation = this.validateImageDimensions(img, this.config.minimage);
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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createBannerForm');
    const createBtn = document.getElementById('createBannerBtn');
    
    form.addEventListener('submit', function(e) {
        // Don't prevent default to allow form submission
        if (createBtn && !createBtn.disabled) {
            createBtn.disabled = true;
            createBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
        }
    });
    
    // Reset form state if there are validation errors
    @if($errors->any())
        if (createBtn) {
            createBtn.disabled = false;
            createBtn.innerHTML = '<i class="bx bx-save me-1"></i>Update Banner';
        }
    @endif
});
</script>
@endsection
