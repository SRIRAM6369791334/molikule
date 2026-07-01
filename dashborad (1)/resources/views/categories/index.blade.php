@extends('layouts.app')
@section('title') Categories | Enterprise Dashboard @endsection

@section('css')
    <style>
        .preview-container, .edit_preview-container, .edit_show_preview-container {
            width: 100%;
            height: 150px;
            border: 2px dashed #d1d1d1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            background: #f8f9fa;
        }
        .preview-container img, .edit_preview-container img, .edit_show_preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dark-asterisk { color: red; margin-left: 3px; }
        .just-validate-error-label {
            color: #ff3d60 !important;
            font-size: 12px !important;
            margin-top: 4px !important;
            display: block !important;
            font-weight: 500;
        }
        .is-invalid {
            border-color: #ff3d60 !important;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Home @endslot
        @slot('title') Category @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="card-title mb-0">Category List</h4>
                            @if(request('q'))
                                <div class="d-inline-flex align-items-center bg-white border rounded-pill shadow-sm overflow-hidden" style="border: 1px solid #e1e1e1 !important;">
                                    <div class="px-3 py-1 bg-light border-end d-flex align-items-center gap-2">
                                        <i class="bx bx-search-alt-2 text-primary"></i>
                                        <span class="text-dark small fw-semibold">Results for: <span class="text-primary">"{{ request('q') }}"</span></span>
                                    </div>
                                    <a href="{{ route('categories.index') }}" class="px-3 py-1 text-danger d-flex align-items-center gap-1 hover-bg-danger-ripple transition-all" style="text-decoration: none;">
                                        <i class="bx bx-x-circle font-size-16"></i>
                                        <span class="small fw-bold">Clear</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoriesModal">
                            <i class="mdi mdi-plus me-1"></i> Add Category
                        </button>
                    </div>
                    
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoriesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="addCategoriesForm" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Category Name<span class="dark-asterisk">*</span></label>
                            <input type="text" class="form-control" name="category_name" id="add_categoriesname" placeholder="Category Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Image<span class="dark-asterisk">*(512x512)</span></label>
                            <input type="file" class="form-control" name="category_image" id="add_categoryImage" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="catActive" checked>
                                <label class="form-check-label" for="catActive">Publish / Active</label>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-3">Theme Settings (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Theme Primary Color</label>
                                <input type="color" class="form-control form-control-color w-100" name="theme_primary_color" id="add_theme_primary_color" value="#bbd700">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Theme Light/Glow Color</label>
                                <input type="color" class="form-control form-control-color w-100" name="theme_light_color" id="add_theme_light_color" value="#bbd700">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Background Overlay Color</label>
                                <input type="color" class="form-control form-control-color w-100" name="theme_bg_overlay" id="add_theme_bg_overlay" value="#f8fafc">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Card Border Radius</label>
                                <select class="form-select select2-auto" name="theme_border_radius" id="add_theme_border_radius">
                                    <option value="">Default (12px)</option>
                                    <option value="4px">Sharp (4px)</option>
                                    <option value="16px">Regular (16px)</option>
                                    <option value="24px">Large (24px)</option>
                                    <option value="40px">Huge (40px)</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Theme Background Image</label>
                                <input type="file" class="form-control" name="theme_bg_image" id="add_theme_bg_image" accept="image/*">
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary add_submit_btn w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoriesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editCategoriesForm" novalidate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_categories_id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Category Name<span class="dark-asterisk">*</span></label>
                            <input type="text" class="form-control" id="edit_categoriesname" name="category_name" required>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-6">
                                <label class="form-label">Current Image</label>
                                <div class="edit_show_preview-container">
                                    <img src="" class="edit_preview_image">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Change Image</label>
                                <input type="file" class="form-control" name="category_image" id="edit_categoryImage" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editCatActive" checked>
                                <label class="form-check-label" for="editCatActive">Publish / Active</label>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-3">Theme Settings (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Theme Primary Color</label>
                                <input type="color" class="form-control form-control-color w-100" name="theme_primary_color" id="edit_theme_primary_color" value="#bbd700">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Theme Light/Glow Color</label>
                                <input type="color" class="form-control form-control-color w-100" name="theme_light_color" id="edit_theme_light_color" value="#bbd700">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Background Overlay Color</label>
                                <input type="color" class="form-control form-control-color w-100" name="theme_bg_overlay" id="edit_theme_bg_overlay" value="#f8fafc">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Card Border Radius</label>
                                <select class="form-select select2-auto" name="theme_border_radius" id="edit_theme_border_radius">
                                    <option value="">Default (12px)</option>
                                    <option value="4px">Sharp (4px)</option>
                                    <option value="16px">Regular (16px)</option>
                                    <option value="24px">Large (24px)</option>
                                    <option value="40px">Huge (40px)</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Theme Background Image</label>
                                <div class="mb-2">
                                    <img src="" id="edit_theme_bg_image_preview" style="max-height: 80px; display: none;" class="rounded border">
                                </div>
                                <input type="file" class="form-control" name="theme_bg_image" id="edit_theme_bg_image" accept="image/*">
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary edit_submit_btn w-100" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.categories = @json($categories);
        window.routes = {
            store: "{{ route('categories.store') }}",
            update: "{{ url('updateCategories') }}",
            destroy: "{{ url('destroyCategories') }}"
        };
    </script>
    <script src="{{ asset('assets/js/app/CategoriesPage.js') }}"></script>
@endpush