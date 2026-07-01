document.addEventListener('DOMContentLoaded', function () {
    "use strict";

    // 1. Initial State & Configuration
    const gridWrapper = document.getElementById("table-gridjs");
    let productGrid = null;

    // 2. Select Component Logic (Cascading Selects)
    const categoryFilter = document.getElementById('sel_category_select');
    const productFilter = document.getElementById('select_product');
    const modalCategory = document.getElementById('modal_category_select');
    const modalProduct = document.getElementById('modal_product_name');

    const brandFilter = document.getElementById('sel_brand_select');
    const modalBrand = document.getElementById('modal_brand_select');

    function updateCategories(brandId, targetCategorySelect, targetProductSelect) {
        let $targetCat = $(targetCategorySelect);
        let $targetProd = $(targetProductSelect);

        console.log('Fetching categories for brand:', brandId);
        $targetCat.html('<option value="">Loading...</option>').trigger('change');
        if (targetProductSelect) {
            $targetProd.html('<option value="">Select Category First</option>').trigger('change');
        }

        let url = `${window.routes.getCategories}?brand_id=${brandId || ''}`;
        console.log('AJAX URL:', url);

        $.get(url, function(data) {
            console.log('Categories received:', data);
            let options = '<option value="">Select Category</option>';
            data.forEach(c => {
                options += `<option value="${c.category_id}">${c.category_name}</option>`;
            });
            $targetCat.html(options).trigger('change');
        }).fail(err => {
            console.error('Error fetching categories:', err);
            $targetCat.html('<option value="">Error Loading Categories</option>').trigger('change');
        });
    }

    function updateProducts(catId, brandId, targetSelect) {
        let $target = $(targetSelect);
        console.log('Fetching products for category:', catId, 'brand:', brandId);
        if (!catId) {
            $target.html('<option value="">Select Category First</option>').trigger('change');
            return;
        }
        $target.html('<option value="">Loading...</option>').trigger('change');

        let url = `${window.routes.getProducts}?category_id=${catId || ''}&brand_id=${brandId || ''}`;
        console.log('AJAX URL:', url);

        $.get(url, function(data) {
            console.log('Products received:', data);
            let options = '<option value="">Select Product.</option>';
            data.forEach(p => {
                options += `<option value="${p.product_id}">${p.name}</option>`;
            });
            $target.html(options).trigger('change');
        }).fail(err => {
            console.error('Error fetching products:', err);
            $target.html('<option value="">Error Loading Products</option>').trigger('change');
        });
    }

    if (brandFilter) {
        // No longer auto-updating categories on filter form to allow independent filtering
        // updateCategories($(this).val(), categoryFilter, productFilter);
    }
    if (categoryFilter) {
        // No longer auto-updating products on filter form to allow independent filtering
        // updateProducts($(this).val(), $(brandFilter).val(), productFilter);
    }
    
    if (modalBrand) {
        $(modalBrand).on('change', function() {
            updateCategories($(this).val(), modalCategory, modalProduct);
        });
    }
    if (modalCategory) {
        $(modalCategory).on('change', function() {
            updateProducts($(this).val(), $(modalBrand).val(), modalProduct);
        });
    }

    // 3. GridJS Implementation (100% Sync with Kumarimall UI)
    const mapVariantToRow = (v) => [
        { image: v.variant_image, id: v.id },
        gridjs.html(`<div class="fw-bold text-primary">${v.variant_name || `${v.value} ${v.variant_unit || ''}`}</div><small class="text-muted">SKU: ${v.sku || 'N/A'}</small>`),
        gridjs.html(`<div class="fw-semibold">${v.product ? v.product.name : 'Unknown'}</div>`),
        v.product && v.product.brand ? v.product.brand.brand_name : 'N/A',
        v.product && v.product.category ? v.product.category.category_name : 'N/A',
        { mrp: v.mrp_price, offer: v.discounted_price },
        v.stock_quantity,
        v.id
    ];

    const renderTable = (variants = []) => {
        if (productGrid) {
            productGrid.updateConfig({ data: variants.map(v => mapVariantToRow(v)) }).forceRender();
            return;
        }

        productGrid = new gridjs.Grid({
            columns: [
                {
                    name: "Image",
                    width: "90px",
                    formatter: (cell) => {
                        const hasImage = cell.image && cell.image !== '';
                        if (hasImage) {
                            return gridjs.html(`<img src="${cell.image}" class="rounded shadow-sm" style="width:45px; height:45px; object-fit:cover; border: 1px solid #eee;">`);
                        } else {
                            return gridjs.html(`
                                <a href="/product-variants/${cell.id}/edit" class="btn btn-sm btn-soft-success d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; padding: 0; font-size: 10px; line-height: 1.1; text-align: center; font-weight: 600; border: 1px dashed #28a745;">
                                    <span>Add<br>Image</span>
                                </a>
                            `);
                        }
                    }
                },
                { name: "Variant Details", width: "200px" },
                { name: "Product", width: "180px" },
                { name: "Brand", width: "120px" },
                { name: "Category", width: "120px" },
                {
                    name: "Price",
                    width: "120px",
                    formatter: (cell) => gridjs.html(`<div class="text-end"><del class="text-muted small">₹${parseFloat(cell.mrp || 0).toFixed(2)}</del><br><span class="text-success fw-bold">₹${parseFloat(cell.offer || 0).toFixed(2)}</span></div>`)
                },
                {
                    name: "Stock",
                    width: "110px",
                    formatter: (cell) => {
                        const color = cell <= 5 ? 'danger' : 'success';
                        return gridjs.html(`<div class="text-center"><span class="badge bg-soft-${color} text-${color} px-2 py-1">${cell} pcs</span></div>`);
                    }
                },
                {
                    name: "Action",
                    width: "100px",
                    formatter: (cell) => gridjs.html(`
                        <div class="d-flex gap-2 justify-content-center">
                             <a href="/product-variants/${cell}/edit" class="btn btn-sm btn-soft-primary" title="Edit">
                                <i class="mdi mdi-pencil font-size-14"></i>
                             </a>
                             <button class="btn btn-sm btn-soft-danger delete-variant-btn" data-id="${cell}" title="Delete">
                                <i class="mdi mdi-trash-can font-size-14"></i>
                             </button>
                        </div>
                    `)
                }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: false, // Using our custom search
            data: variants.map(v => mapVariantToRow(v)),
            className: { 
                table: 'table table-bordered mb-0 align-middle',
                thead: 'table-light'
            }
        }).render(gridWrapper);
    };

    // Load Initial Data (100% Sync with Kumarimall SPA Logic)
    if (window.variants && window.variants.data) {
        renderTable(window.variants.data);
    } else if (window.variants && Array.isArray(window.variants)) {
        renderTable(window.variants);
    } else {
        fetch(window.routes.filterVariants, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => renderTable(data.data || []))
            .catch(err => console.error('Initial load failed:', err));
    }

    // 4. Form Submission & Image Validation (Kumarimall exact)
    const validateDimensions = (file) => {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                const img = new Image();
                img.src = e.target.result;
                img.onload = () => {
                    resolve(img.width === 600 && img.height === 600);
                };
            };
        });
    };

    const addForm = document.getElementById('addProductvarientForm');
    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.querySelector('.addvari_submit_btn');

            // Image Validation
            const imgInput = document.getElementById('add_varient_image');
            if (imgInput.files.length > 0) {
                const isValid = await validateDimensions(imgInput.files[0]);
                if (!isValid) {
                    Swal.fire('Format Error', 'Variant image must be exactly 600x600 pixels!', 'error');
                    return;
                }
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Saving...';

            const formData = new FormData(this);
            fetch(window.routes.storeVariant, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_csrf-token"]')?.value || '' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.message, 'success');
                        addForm.reset();
                        document.getElementById('main-preview-img').classList.add('d-none');
                        document.querySelector('#preview-container1 div').classList.remove('d-none');
                        bootstrap.Modal.getInstance(document.getElementById('addProductvariModal')).hide();
                        renderTable(data.variants);
                    } else {
                        Swal.fire('Error', data.message || 'Check form fields', 'error');
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = 'Save Variant';
                });
        });
    }

    // 5. Image Preview Preview Logic
    const setupPreview = (inputID, imgID, containerID) => {
        const input = document.getElementById(inputID);
        if (!input) return;
        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.getElementById(imgID);
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                    const textDiv = document.querySelector(`#${containerID} div`);
                    if (textDiv) textDiv.classList.add('d-none');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    };

    setupPreview('add_varient_image', 'main-preview-img', 'preview-container1');
    setupPreview('edit_varient_image_input', 'edit_new_main_preview', 'preview-container2');

    // 6. Multi-Thumbnail Logic (Gallery)
    const addInputBtn = document.getElementById('add-input1');
    const dynamicContainer = document.getElementById('dynamic-inputs1');
    if (addInputBtn) {
        addInputBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'product_fields1 mb-3';
            div.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <input type="file" class="form-control" name="product_image1[]" accept="image/*">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger btn-sm delete-input1">Delete</button>
                    </div>
                </div>
            `;
            dynamicContainer.appendChild(div);
        });
    }

    // 8. Filter Form Submission (AJAX Update)
    const filterForm = document.getElementById('productverfilterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = document.querySelector('.productver_filter_btn');
            const originalBtnHtml = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Filtering...';

            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            const url = `${window.routes.filterVariants}?${params}`;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    renderTable(data.data || []);
                    if (data.data && data.data.length === 0) {
                        Swal.fire('Info', 'No variants found for these filters', 'info');
                    }
                })
                .catch(err => {
                    console.error('Filter failed:', err);
                    Swal.fire('Error', 'Failed to filter variants', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalBtnHtml;
                });
        });
    }

    // 7. AJAX Refresh helper (if needed)
    window.refreshVariantTable = (variants) => {
        renderTable(variants);
    };

    // Bulk Upload Variants submit handler
    $(document).on("submit", "#bulkUploadVariantsForm", function (e) {
        e.preventDefault();
        const submitBtn = $(this).find(".bulk_submit_btn");
        submitBtn.attr("disabled", true).html("Uploading...");

        const formdata = new FormData(this);
        $.ajax({
            url: "/product-variants/bulk-upload",
            method: "POST",
            dataType: "json",
            data: formdata,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                submitBtn.removeAttr("disabled").html("Start Upload");
                $("#bulkUploadVariantsForm")[0].reset();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkUploadVariantsModal')).hide();
                
                // Refresh table grid
                renderTable(response.variants || []);
                Swal.fire("Success", response.message, "success");
            },
            error: function (jqXHR) {
                submitBtn.removeAttr("disabled").html("Start Upload");
                let errorMsg = "Failed to upload file";
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMsg = jqXHR.responseJSON.message;
                }
                Swal.fire("Error", errorMsg, "error");
            }
        });
    });
});
