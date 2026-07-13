@extends('layouts.app')
@section('title')
    Product Variants | Enterprise Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css">
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .preview-container, .edit_preview-container, .edit_show_preview-container, .edit_show_preview-containernew {
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
        .preview-container img, .edit_preview-container img, .edit_show_preview-container img, .edit_show_preview-containernew img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image_el { display: none; }
        .dynamic-inputs1, .dynamic-inputsedit {
            border: 1px solid #ebedf2;
            padding: 15px;
            border-radius: 5px;
            background: #fcfcfc;
        }
        /* Premium Select2 Styling */
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            padding: 8px !important;
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .select2-dropdown {
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            z-index: 1070; /* Above modals */
        }
        /* Highlight Flavor field */
        .flavor-highlight {
            border-left: 4px solid #34c38f !important;
            background-color: #f0fff4 !important;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Home @endslot
        @slot('title') Product Variant @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="fw-bold mb-0">Search Filter:</p>
                        @if(request('q'))
                            <div class="d-inline-flex align-items-center bg-white border rounded-pill shadow-sm overflow-hidden" style="border: 1px solid #e1e1e1 !important;">
                                <div class="px-3 py-1 bg-light border-end d-flex align-items-center gap-2">
                                    <i class="bx bx-search-alt-2 text-primary"></i>
                                    <span class="text-dark small fw-semibold">Results for: <span class="text-primary">"{{ request('q') }}"</span></span>
                                </div>
                                <a href="{{ route('product-variants.index') }}" class="px-3 py-1 text-danger d-flex align-items-center gap-1 hover-bg-danger-subtle transition-all" style="text-decoration: none;">
                                    <i class="bx bx-x-circle font-size-16"></i>
                                    <span class="small fw-bold">Clear</span>
                                </a>
                            </div>
                        @endif
                    </div>
                    <form id="productverfilterForm" novalidate>
                        <div class="row align-items-end g-3">
                            <div class="col-md-2">
                                <label class="form-label">Choose Brand</label>
                                <select class="form-select" name="brand_id" id="sel_brand_select">
                                    <option value="" selected>All Brands</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Choose Category</label>
                                <select class="form-select" name="category_id" id="sel_category_select">
                                    <option value="" selected>All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Choose Product</label>
                                <select class="form-select" name="product_id" id="select_product">
                                    <option value="" selected>All Products</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-success w-100 productver_filter_btn">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#bulkUploadVariantsModal">
                                    <i class="mdi mdi-upload me-1"></i> Bulk Upload
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('product-variants.create') }}" class="btn btn-primary w-100">
                                    <i class="mdi mdi-plus me-1"></i> Add Variant
                                </a>
                            </div>
                        </div>
                    </form>

                    <div id="table-gridjs" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Variant Modal -->
    <div class="modal fade" id="addProductvariModal" tabindex="-1" aria-labelledby="addProductvariModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductvariModalLabel">Add Product Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form class="needs-validation" id="addProductvarientForm" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Brand*</label>
                                <select class="form-select" name="brand_id" id="modal_brand_select" required>
                                    <option value="" disabled selected>Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category*</label>
                                <select class="form-select" name="category_id" id="modal_category_select" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Product*</label>
                                <select class="form-select" name="product_id" id="modal_product_name" required>
                                    <option value="" disabled selected>Select Product.</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Variant Main Image (600 * 600)*</label>
                                <label for="add_varient_image" class="preview-container" id="preview-container1">
                                    <div class="text-center">
                                        <i class="display-4 text-muted mdi mdi-cloud-upload"></i>
                                        <div class="mt-1">Upload Main Image</div>
                                    </div>
                                    <img src="#" id="main-preview-img" class="d-none">
                                </label>
                                <input type="file" class="image_el" id="add_varient_image" name="variant_image" accept="image/*" required>
                            </div>

                             <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Flavor Name / Scent*</label>
                                <input type="text" class="form-control flavor-highlight" 
                                       name="variant_name" 
                                       list="flavourList"
                                       placeholder="e.g. Lavender, Rose, Lemon" required>
                                <datalist id="flavourList">
                                    @foreach($existingNames ?? [] as $name)
                                        @php 
                                            $flavour = trim(explode('–', $name)[0]);
                                            $flavour = trim(explode('-', $flavour)[0]);
                                            $flavour = trim(explode('/', $flavour)[0]);
                                        @endphp
                                        <option value="{{ $flavour }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" name="sku" placeholder="SKU Code">
                            </div>

                             <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Type*</label>
                                <select class="form-select" name="unit_id" required>
                                    <option value="">Select Unit</option>
                                    <option value="1">Liters (l)</option>
                                    <option value="2">Milliliters (ml)</option>
                                    <option value="3">Grams (g)</option>
                                    <option value="4">Kilograms (kg)</option>
                                    <option value="5">No's (Pieces)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Quantity Value*</label>
                                <input type="text" class="form-control" name="value" placeholder="e.g. 500" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">MRP Price*</label>
                                <input type="number" step="0.01" class="form-control" name="mrp_price" placeholder="Original Price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-success fw-bold">Selling Price (Offer)*</label>
                                <input type="number" step="0.01" class="form-control" name="offer_price" placeholder="Selling Price" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Quantity*</label>
                                <input type="number" class="form-control" name="stock_quantity" value="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Low Stock Warning*</label>
                                <input type="number" class="form-control" name="low_stock" value="5" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="add_is_featured">
                                    <label class="form-check-label fw-bold" for="add_is_featured">Mark as Featured</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_trending" value="1" id="add_is_trending">
                                    <label class="form-check-label fw-bold" for="add_is_trending">Mark as Trending</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <h6 class="border-bottom pb-2">Variant Thumbnail Gallery</h6>
                                <div id="dynamic-inputs1" class="dynamic-inputs1">
                                    <div class="product_fields1 mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-9">
                                                <input type="file" class="form-control" name="variant_gallery[]" accept="image/*">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-danger btn-sm delete-input1">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-input1" class="btn btn-outline-success btn-sm mt-2">Add Another Image</button>
                            </div>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary addvari_submit_btn px-4">Save Variant</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Variant Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editProductVarientForm" novalidate enctype="multipart/form-data">
                        <input type="hidden" id="edit_productvar_id" name="id">
                        <div class="row">
                            <!-- Category/Product (Read-only recommendation) -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Brand</label>
                                <select class="form-select" id="edit_brand_select" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" id="edit_category_select" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Product</label>
                                <select class="form-select" id="edit_product_name" name="product_id" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 text-center">
                                <label class="form-label d-block text-start">Current Main Image</label>
                                <div class="edit_show_preview-container">
                                    <img src="" class="edit_preview_image" id="edit_current_main_img">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 text-center">
                                <label class="form-label d-block text-start">Replace Main Image</label>
                                <label for="edit_varient_image_input" class="edit_preview-container">
                                    <div class="text-center">
                                        <i class="display-4 text-muted mdi mdi-cloud-upload" style="font-size: 24px"></i>
                                        <div>Click to Change</div>
                                    </div>
                                    <img src="#" class="d-none" id="edit_new_main_preview">
                                </label>
                                <input type="file" id="edit_varient_image_input" class="image_el" name="variant_image" accept="image/*">
                            </div>

                             <div class="col-md-4 mb-3">
                                <label class="form-label">Variant Label</label>
                                <input type="text" class="form-control" id="edit_variant_name" 
                                       name="variant_name" list="flavourList">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" id="edit_sku" name="sku">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Type*</label>
                                <select class="form-select" name="unit_id" id="edit_unit_select" required>
                                    <option value="1">Liters (l)</option>
                                    <option value="2">Milliliters (ml)</option>
                                    <option value="3">Grams (g)</option>
                                    <option value="4">Kilograms (kg)</option>
                                    <option value="5">No's (Pieces)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Value*</label>
                                <input type="text" class="form-control" id="edit_value" name="value" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">MRP Price*</label>
                                <input type="number" step="0.01" class="form-control" id="edit_mrp_price" name="mrp_price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-success fw-bold">Selling Price*</label>
                                <input type="number" step="0.01" class="form-control" id="edit_offer_price" name="offer_price" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Quantity*</label>
                                <input type="number" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Low Stock*</label>
                                <input type="number" class="form-control" id="edit_low_stock" name="low_stock" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_is_featured">
                                    <label class="form-check-label fw-bold" for="edit_is_featured">Mark as Featured</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_trending" value="1" id="edit_is_trending">
                                    <label class="form-check-label fw-bold" for="edit_is_trending">Mark as Trending</label>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary editver_submit_btn px-4">Update Variant</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Variants Modal -->
    <div class="modal fade" id="bulkUploadVariantsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Variants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkUploadVariantsForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Upload CSV File</label>
                            <input type="file" class="form-control" name="file" accept=".csv" required>
                        </div>
                        <div class="alert alert-info py-2 small mb-3">
                            <strong>Note:</strong> Variant images will be set to optional during import. You can edit them manually later.
                        </div>
                        <div class="text-end">
                            <a href="{{ route('product-variants.download-template') }}" class="btn btn-link text-decoration-none p-0 small">
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
        // Inject data for GridJS
        window.variants = @json($variants ?? []);
        window.products = @json($products ?? []);

        // Inject configuration exactly like Kumarimall
        window.can_create_product = true;
        window.can_edit_product = true;
        window.can_delete_product = true;
        
        // Routes mapping
        window.routes = {
            getCategories: "{{ route('product-variants.get-categories', [], false) }}",
            getProducts: "{{ route('product-variants.get-products', [], false) }}",
            storeVariant: "{{ route('product-variants.ajax-store', [], false) }}",
            updateVariant: "{{ url('product-variants/update') }}",
            deleteVariant: "{{ url('product-variants/destroy') }}",
            filterVariants: "{{ route('product-variants.index', [], false) }}"
        };
    </script>
    <script src="{{ URL::asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // 1. Initialize All Select2 elements
            const initSelect2 = () => {
                $('#sel_category_select, #sel_brand_select, #select_product, #modal_category_select, #modal_brand_select, #modal_product_name, #edit_category_select, #edit_brand_select, #edit_product_name, .modal select').select2({
                    width: '100%',
                });
            };
            initSelect2();

            // Triple Cascading Logic for Edit Modal
            $('#edit_brand_select').on('change', function() {
                const brandId = $(this).val();
                const categorySelect = $('#edit_category_select');
                const productSelect = $('#edit_product_name');

                categorySelect.html('<option value="">Loading...</option>').trigger('change');
                productSelect.html('<option value="">Select Category First</option>').trigger('change');

                $.get("{{ route('product-variants.get-categories', [], false) }}", { brand_id: brandId }, function(data) {
                    let options = '<option value="">Select Category</option>';
                    data.forEach(c => options += `<option value="${c.category_id}">${c.category_name}</option>`);
                    categorySelect.html(options).trigger('change');
                    
                    // IF we are in the middle of an edit, set the selected category
                    if (window.pendingEditCategoryId) {
                        categorySelect.val(window.pendingEditCategoryId).trigger('change');
                        window.pendingEditCategoryId = null; // Clear it
                    }
                });
            });

            $('#edit_category_select').on('change', function() {
                const catId = $(this).val();
                const brandId = $('#edit_brand_select').val();
                const productSelect = $('#edit_product_name');

                if (!catId) return;

                productSelect.html('<option value="">Loading...</option>').trigger('change');
                $.get("{{ route('product-variants.get-products', [], false) }}", { category_id: catId, brand_id: brandId }, function(data) {
                    let options = '<option value="">Select Product</option>';
                    data.forEach(p => options += `<option value="${p.product_id}">${p.name}</option>`);
                    productSelect.html(options).trigger('change');
                    
                    // IF we are in the middle of an edit, set the selected product
                    if (window.pendingEditProductId) {
                        productSelect.val(window.pendingEditProductId).trigger('change');
                        window.pendingEditProductId = null; // Clear it
                    }
                });
            });

            // 2. Adjust Select2 for Modal specificity
            $('#addProductvariModal, #editProductModal').on('shown.bs.modal', function () {
                $(this).find('select').select2({
                    dropdownParent: $(this),
                    width: '100%'
                });
            });

            // 3. FIX ACTION BUTTONS (Edit/Delete) - Delegate to document for GridJS compatibility




            // 5. Handle Delete (Kumarimall Logic Backup)
            $(document).on('click', '.delete-variant-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Variant?',
                    text: "Action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f46a6a',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post(`/product-variants/destroy/${id}`, {
                            _token: '{{ csrf_token() }}'
                        }, function(res) {
                            Swal.fire('Deleted!', 'Variant has been removed.', 'success');
                            location.reload();
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{ URL::asset('assets/js/app/ProductVarientPage.js') }}"></script>
@endpush