@extends('layouts.app')
@section('title') Brands | Enterprise Dashboard @endsection

@section('css')
    <style>
        .edit_show_preview-container {
            width: 100%;
            height: 120px;
            border: 1px solid #eee;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            overflow: hidden;
        }
        .edit_show_preview-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .dark-asterisk { color: red; margin-left: 3px; }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Home @endslot
        @slot('title') Brands @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="card-title mb-0">Brand Management</h4>
                            @if(request('q'))
                                <div class="d-inline-flex align-items-center bg-white border rounded-pill shadow-sm overflow-hidden" style="border: 1px solid #e1e1e1 !important;">
                                    <div class="px-3 py-1 bg-light border-end d-flex align-items-center gap-2">
                                        <i class="bx bx-search-alt-2 text-primary"></i>
                                        <span class="text-dark small fw-semibold">Results for: <span class="text-primary">"{{ request('q') }}"</span></span>
                                    </div>
                                    <a href="{{ route('brands.index') }}" class="px-3 py-1 text-danger d-flex align-items-center gap-1 hover-bg-danger-ripple transition-all" style="text-decoration: none;">
                                        <i class="bx bx-x-circle font-size-16"></i>
                                        <span class="small fw-bold">Clear</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bulkUploadBrandsModal">
                                <i class="mdi mdi-upload me-1"></i> Bulk Upload
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandsModal">
                                <i class="mdi mdi-plus me-1"></i> Add Brand
                            </button>
                        </div>
                    </div>
                    
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Brand Modal -->
    <div class="modal fade" id="addBrandsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="addBrandsForm" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Brand Name<span class="dark-asterisk">*</span></label>
                            <input type="text" class="form-control" name="brand_name" id="add_brandname" placeholder="e.g. Molikule" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand Logo <small class="text-muted">(1024x1024)</small><span class="dark-asterisk">*</span></label>
                            <input type="file" class="form-control" name="logo" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch form-switch-md">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="brandActive" checked>
                                <label class="form-check-label" for="brandActive">Verified / Active Status</label>
                            </div>
                        </div>
                        <button class="btn btn-primary add_submit_btn w-100" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editBrandsForm" novalidate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_brand_id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Brand Name*</label>
                            <input type="text" class="form-control" id="edit_brandname" name="brand_name" required>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-6">
                                <label class="form-label text-muted small">Current Logo</label>
                                <div class="edit_show_preview-container">
                                    <img src="" class="edit_preview_image">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Replace Logo <small class="text-muted">(1024x1024)</small></label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch form-switch-md">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editBrandActive" checked>
                                <label class="form-check-label" for="editBrandActive">Verified / Active Status</label>
                            </div>
                        </div>
                        <button class="btn btn-primary edit_submit_btn w-100" type="submit">Update Brand</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadBrandsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Brands</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkUploadBrandsForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Upload CSV File</label>
                            <input type="file" class="form-control" name="file" accept=".csv" required>
                        </div>
                        <div class="alert alert-info py-2 small mb-3">
                            <strong>Note:</strong> Brand logos will be set to optional during import. You can upload logos manually later by editing individual brands.
                        </div>
                        <div class="text-end">
                            <a href="{{ route('brands.download-template') }}" class="btn btn-link text-decoration-none p-0 small">
                                <i class="mdi mdi-download"></i> Download Sample CSV Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary bulk_submit_btn">Start Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.brands = @json($brands);
        window.routes = {
            store: "{{ route('brands.store') }}",
            update: "{{ url('updateBrands') }}",
            destroy: "{{ url('destroyBrands') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/BrandsPage.js') }}"></script>
@endpush