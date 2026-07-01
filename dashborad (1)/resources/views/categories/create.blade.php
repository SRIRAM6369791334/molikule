@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Category Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Add New Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-success-subtle text-success">
                            <i class="bx bx-category font-size-18"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Create New Category</h5>
                        <p class="text-muted small mb-0">Organize your Molikule products into groups</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="category_name" class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light-subtle @error('category_name') is-invalid @enderror" 
                                    id="category_name" name="category_name" placeholder="E.g., Floor Cleaners, Sanitizers" value="{{ old('category_name') }}" required>
                                @error('category_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="parent_id" class="form-label fw-bold">Parent Category</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                    <option value="" selected>None (Top Level)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="sort_order" class="form-label fw-bold">Display Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                            </div>

                            <div class="col-md-12 mb-4 text-center">
                                <label class="form-label fw-bold d-block text-start">Category Cover Image <span class="text-danger">*</span></label>
                                <div class="category-image-upload border-2 border-dashed rounded-4 p-4 text-center cursor-pointer mb-2" id="image-dropzone">
                                    <input type="file" name="image" id="category_image" class="d-none" accept="image/*" required>
                                    <label for="category_image" class="mb-0 cursor-pointer">
                                        <div id="image-placeholder">
                                            <i class="bx bx-cloud-upload display-4 text-muted mb-2"></i>
                                            <h5>Click to Upload or Drag & Drop</h5>
                                            <p class="text-muted small">(PNG, JPG, WEBp - Recommended 800x800px)</p>
                                        </div>
                                        <div id="image-preview-container" class="d-none">
                                            <img src="#" id="image-preview" class="img-fluid rounded-4 shadow-sm" style="max-height: 200px;">
                                            <div class="mt-2">
                                                <span class="text-primary small fw-bold">Change Image</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-text text-start">Images will be displayed on the storefront and app.</div>
                            </div>
                        </div>

                        <hr class="my-4 border-light">
                        <h6 class="fw-bold mb-3">Theme Settings</h6>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="theme_primary_color" class="form-label fw-bold">Primary Color</label>
                                <input type="color" class="form-control form-control-color w-100" id="theme_primary_color" name="theme_primary_color" value="#bbd700">
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="theme_light_color" class="form-label fw-bold">Light Glow Color (rgba)</label>
                                <input type="text" class="form-control" id="theme_light_color" name="theme_light_color" value="rgba(187, 215, 0, 0.3)" placeholder="rgba(187, 215, 0, 0.3)">
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="theme_border_radius" class="form-label fw-bold">Card Shape</label>
                                <select class="form-select" id="theme_border_radius" name="theme_border_radius">
                                    <option value="4px">Sharp (4px)</option>
                                    <option value="20px" selected>Round (20px)</option>
                                    <option value="40px">Pill (40px)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="theme_bg_overlay" class="form-label fw-bold">Background Overlay Tint</label>
                                <input type="text" class="form-control" id="theme_bg_overlay" name="theme_bg_overlay" value="rgba(255,255,255,0.95)" placeholder="rgba(255,255,255,0.95)">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="theme_bg_image" class="form-label fw-bold">Theme Background Image</label>
                                <input type="file" class="form-control" id="theme_bg_image" name="theme_bg_image" accept="image/*">
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="fw-bold mb-0">Options & Visibility</h6>
                            </div>
                            <div class="d-flex gap-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label fw-bold" for="is_active">Active</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                    <label class="form-check-label fw-bold" for="is_featured">Featured</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3 shadow-sm rounded-3">
                                <i class="bx bx-save me-2 font-size-18"></i>Create Category
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-light py-2">Cancel and Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .category-image-upload { background: #fcfcfc; transition: all 0.3s; }
        .category-image-upload:hover { border-color: var(--bs-primary) !important; background: rgba(var(--bs-primary-rgb), 0.02); }
        .cursor-pointer { cursor: pointer; }
        .bg-light-subtle { background-color: #fafafa !important; }
    </style>

    @push('scripts')
    <script>
        document.getElementById('category_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('image-placeholder').classList.add('d-none');
                    document.getElementById('image-preview').src = event.target.result;
                    document.getElementById('image-preview-container').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
@endsection