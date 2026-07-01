@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Brand Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Molikule</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Add Brand</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                            <i class="bx bx-award font-size-18"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Register New Brand</h5>
                        <p class="text-muted small mb-0">Add manufacturers or brand labels for Molikule inventory</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" id="brandForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-4">
                            <label for="brand_name" class="form-label fw-bold">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light-subtle @error('brand_name') is-invalid @enderror" 
                                id="brand_name" name="brand_name" placeholder="E.g., Molikule Home, Royal Green" value="{{ old('brand_name') }}" required>
                            @error('brand_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold d-block text-start">Brand Logo <span class="text-danger">*</span></label>
                            <div class="brand-logo-upload border-2 border-dashed rounded-4 p-5 text-center cursor-pointer mb-2" id="logo-dropzone">
                                <input type="file" name="logo" id="brand_logo" class="d-none" accept="image/*" required>
                                <label for="brand_logo" class="mb-0 cursor-pointer h-100 w-100">
                                    <div id="logo-placeholder">
                                        <i class="bx bx-cloud-upload display-3 text-muted mb-2"></i>
                                        <h4>Upload Brand Logo</h4>
                                        <p class="text-muted small">JPG, PNG, WebP (Square 1024x1024px for best result)</p>
                                    </div>
                                    <div id="logo-preview-container" class="d-none animate__animated animate__zoomIn">
                                        <div class="logo-preview-wrapper mx-auto p-2 bg-white rounded-circle shadow-sm border" style="width: 150px; height: 150px;">
                                            <img src="#" id="logo-preview" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                        <div class="mt-3">
                                            <span class="btn btn-outline-primary btn-sm rounded-pill px-3">Replace Logo</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch form-switch-md mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Active Status</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch form-switch-md mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                                    <label class="form-check-label" for="is_featured">Featured Brand</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3 shadow-sm rounded-3">
                                <i class="bx bx-check-double me-2 font-size-18"></i>Register Brand
                            </button>
                            <a href="{{ route('brands.index') }}" class="btn btn-link text-muted">Discard and Return</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .brand-logo-upload { background: #fdfdfd; transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1); }
        .brand-logo-upload:hover { border-color: var(--bs-primary) !important; background: rgba(var(--bs-primary-rgb), 0.02); }
        .cursor-pointer { cursor: pointer; }
        .bg-light-subtle { background-color: #fbfbfb !important; }
        .animate__zoomIn { animation-duration: 0.5s; }
    </style>

    @push('scripts')
    <script>
        document.getElementById('brand_logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('logo-placeholder').classList.add('d-none');
                    document.getElementById('logo-preview').src = event.target.result;
                    document.getElementById('logo-preview-container').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
@endsection