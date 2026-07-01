@extends('layouts.app')
@section('title') Subcategories | Enterprise Dashboard @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Hierarchy @endslot
        @slot('title') Subcategories @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="card-title mb-0">Subcategory Management</h4>
                        <button class="btn btn-primary" id="addBtn">
                            <i class="mdi mdi-plus me-1"></i> Add Subcategory
                        </button>
                    </div>
                    <div id="table-gridjs"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="subcategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subcategoryForm">
                    <div class="modal-body">
                        <input type="hidden" id="category_id" name="category_id">
                        
                        <div class="mb-3">
                            <label class="form-label">Parent Category *</label>
                            <select class="form-select select2-auto" id="parent_id" name="parent_id">
                                <option value="">Select Parent</option>
                                @foreach($parent_categories as $parent)
                                    <option value="{{ $parent->category_id }}">{{ $parent->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subcategory Name *</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image (1:1 Ratio)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Recommended: 400x400px</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="active" name="active">
                                <option value="1">Active</option>
                                <option value="0">Hidden</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/just-validate@latest/dist/just-validate.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
    <script>
        window.subcategories = @json($subcategories);
    </script>
    <script src="{{ asset('assets/js/app/SubcategoriesPage.js') }}"></script>
@endpush
