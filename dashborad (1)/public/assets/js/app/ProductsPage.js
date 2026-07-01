/**
 * ProductsPage.js
 * Handles validation for Add/Edit forms and Grid rendering for the Product List
 */

document.addEventListener("DOMContentLoaded", function () {
    // 1. GRID RENDERING (If on Index Page)
    const gridElement = document.getElementById("table-gridjs");
    if (gridElement && window.products) {
        renderProductGrid(window.products, gridElement);
    }

    // 2. FORM VALIDATION (If on Add/Edit Page)
    const productForm = document.getElementById("productForm");
    if (productForm) {
        initProductFormValidation(productForm);
    }
});

let gridNewProduct = null;

/**
 * Renders the Grid.js table for Products
 */
function renderProductGrid(products, wrapper) {
    if (!wrapper) return;

    if (gridNewProduct) {
        gridNewProduct.updateConfig({
            data: products.map((p, index) => [
                index + 1,
                p.name,
                p.image_url || p.image || '',
                p.category ? p.category.category_name : 'No Category',
                p.brand ? p.brand.brand_name : 'No Brand',
                p.active,
                p.product_id
            ])
        }).forceRender();
        return;
    }

    gridNewProduct = new gridjs.Grid({
        columns: [
            "S.NO",
            {
                name: "Product",
                formatter: (cell, row) => {
                    const img = row.cells[2].data || '';
                    const category = row.cells[3].data;
                    const id = row.cells[6].data;
                    
                    const hasImage = img && !img.includes('img-1.png') && !img.includes('placeholder.png');
                    
                    let imageHtml = '';
                    if (hasImage) {
                        imageHtml = `<img src="${img}" alt="" class="rounded me-3" style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #eee;">`;
                    } else {
                        imageHtml = `
                            <a href="/products/${id}/edit" class="btn btn-sm btn-soft-success me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; padding: 0; font-size: 10px; line-height: 1.1; text-align: center; font-weight: 600; border: 1px dashed #28a745;">
                                <span>Add<br>Image</span>
                            </a>
                        `;
                    }
                    
                    return gridjs.html(`
                        <div class="d-flex align-items-center">
                            ${imageHtml}
                            <div style="line-height: 1.2;">
                                <h6 class="text-truncate mb-1" style="max-width: 180px; font-weight: 600;">${cell}</h6>
                                <span class="badge bg-soft-info text-info small" style="font-size: 10px;">${category}</span>
                            </div>
                        </div>
                    `);
                }
            },
            { name: "Image", hidden: true },
            { name: "Category", hidden: true },
            {
                name: "Brand",
                formatter: (cell) => gridjs.html(`<span class="text-muted fw-medium">${cell}</span>`)
            },
            {
                name: "Status",
                formatter: (cell) => {
                    const badgeClass = cell ? 'bg-success' : 'bg-secondary';
                    const text = cell ? 'Active' : 'Inactive';
                    return gridjs.html(`<span class="badge ${badgeClass} rounded-pill px-3">${text}</span>`);
                }
            },
            {
                name: "Action",
                sort: false,
                formatter: (_, row) => {
                    const id = row.cells[6].data;
                    return gridjs.html(`
                        <div class="d-flex gap-2">
                            <a href="/products/${id}/edit" class="btn btn-sm btn-soft-primary px-3">
                                <i class="mdi mdi-pencil me-1"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-soft-danger px-3 delete-product-btn" data-id="${id}">
                                <i class="mdi mdi-trash-can me-1"></i> Delete
                            </button>
                        </div>
                    `);
                }
            },
            { name: "ID", hidden: true }
        ],
        pagination: { limit: 10 },
        sort: true,
        search: true,
        data: products.map((p, index) => [
            index + 1,
            p.name,
            p.image_url || p.image || '',
            p.category ? p.category.category_name : 'No Category',
            p.brand ? p.brand.brand_name : 'No Brand',
            p.active,
            p.product_id
        ]),
        className: {
            table: 'table table-hover align-middle mb-0',
            thead: 'table-light'
        },
        language: {
            search: { placeholder: 'Search products...' }
        }
    }).render(wrapper);

    // Handle Delete
    $(document).on("click", ".delete-product-btn", function () {
        const id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the product and its variants!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#f46a6a",
            cancelButtonColor: "#74788d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/products/${id}`,
                    method: "DELETE",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        Swal.fire("Deleted!", "Product has been deleted.", "success").then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire("Error", xhr.responseJSON?.message || "Failed to delete product", "error");
                    }
                });
            }
        });
    });
}

/**
 * Initializes JustValidate for Product Forms
 */
function initProductFormValidation(form) {
    const validator = new window.JustValidate("#productForm", {
        errorFieldCssClass: "is-invalid",
        errorLabelCssClass: 'just-validate-error-label',
        errorLabelStyle: {
            color: "#f46a6a",
            fontSize: "12px",
            marginTop: "4px",
        },
    });

    // Helper to safely add fields only if they exist in DOM
    const safeAddField = (selector, rules) => {
        if (document.querySelector(selector)) {
            validator.addField(selector, rules);
        }
    };

    // 1. Details Validation
    safeAddField("#name", [
        { rule: "required", errorMessage: "*Product Name is required" },
        { rule: "minLength", value: 3, errorMessage: "*Name too short (min 3 chars)" },
    ]);
    safeAddField("#category_id", [{ rule: "required", errorMessage: "*Category selection is required" }]);
    safeAddField("#brand_id", [{ rule: "required", errorMessage: "*Brand selection is required" }]);
    safeAddField("#description", [{ rule: "required", errorMessage: "*Detailed specification is required" }]);
    safeAddField("#short_description", [{ rule: "required", errorMessage: "*Short summary is required" }]);
    safeAddField("#made_in", [{ rule: "required", errorMessage: "*Origin country is required" }]);
    safeAddField("#weight", [{ rule: "required", errorMessage: "*Weight is required" }]);
    safeAddField("#weight_unit", [{ rule: "required", errorMessage: "*Weight unit required" }]);
    safeAddField("#length", [{ rule: "required", errorMessage: "*Length required" }]);
    safeAddField("#width", [{ rule: "required", errorMessage: "*Width required" }]);
    safeAddField("#height", [{ rule: "required", errorMessage: "*Height required" }]);
    safeAddField("#dimension_unit", [{ rule: "required", errorMessage: "*Dimension unit required" }]);

    // 2. Base Inventory (Only on Add Page)
    safeAddField("#initial_flavour", [{ rule: "required", errorMessage: "*Flavour/Scent is required" }]);
    safeAddField("#initial_unit", [{ rule: "required", errorMessage: "*Size/Volume is required" }]);
    safeAddField('input[name="variant_mrp"]', [
        { rule: "required", errorMessage: "*Retail Price is required" },
        { rule: "number", errorMessage: "*Must be a valid number" }
    ]);
    safeAddField('input[name="variant_stock"]', [
        { rule: "required", errorMessage: "*Stock is required" },
        { rule: "number", errorMessage: "*Must be a valid number" }
    ]);
    safeAddField('input[name="variant_sku"]', [{ rule: "required", errorMessage: "*Unique SKU is required" }]);

    // 3. Image Validation (1080x1080)
    const imageInput = document.getElementById("product_image");
    safeAddField("#product_image", [
        {
            validator: (files) => {
                if (imageInput && imageInput.hasAttribute('required')) {
                    return files && files.length > 0;
                }
                return true;
            },
            errorMessage: '*Product Image is required',
        },
        {
            rule: 'files',
            value: {
                files: {
                    extensions: ['jpeg', 'jpg', 'png', 'webp'],
                    maxSize: 5120000,
                    types: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                },
            },
            errorMessage: '*Invalid image (JPEG/PNG/WEBP, Max 5MB)',
        },
        {
            validator: (files) => {
                if (!files || files.length === 0) return true;
                const file = files[0];
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = function () {
                        const result = this.width === 1080 && this.height === 1080;
                        resolve(result);
                    };
                    img.onerror = () => resolve(false);
                    img.src = URL.createObjectURL(file);
                });
            },
            errorMessage: "*Image must be exactly 1080x1080 pixels"
        }
    ]);

    validator.onSuccess((event) => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Processing...';
        }
        form.submit();
    });
}

// Bulk Upload Products submit handler
$(document).on("submit", "#bulkUploadProductsForm", function (e) {
    e.preventDefault();
    const submitBtn = $(".bulk_submit_btn");
    submitBtn.attr("disabled", true).html("Uploading...");

    const formdata = new FormData(this);
    $.ajax({
        url: "/products/bulk-upload",
        method: "POST",
        dataType: "json",
        data: formdata,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            submitBtn.removeAttr("disabled").html("Start Upload");
            $("#bulkUploadProductsForm")[0].reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkUploadProductsModal')).hide();
            
            // Refresh table grid
            renderProductGrid(response.products, document.getElementById("table-gridjs"));
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
