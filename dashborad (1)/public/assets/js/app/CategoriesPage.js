const addValidator = new JustValidate("#addCategoriesForm", {
    validateBeforeSubmitting: true,
    errorFieldCssClass: 'is-invalid',
    errorLabelCssClass: 'just-validate-error-label',
    errorLabelStyle: {
        color: '#ff3d60',
        fontSize: '12px',
        marginTop: '5px'
    }
});
const editValidator = new JustValidate("#editCategoriesForm", {
    validateBeforeSubmitting: true,
    errorFieldCssClass: 'is-invalid',
    errorLabelCssClass: 'just-validate-error-label',
    errorLabelStyle: {
        color: '#ff3d60',
        fontSize: '12px',
        marginTop: '5px'
    }
});

// Use injected routes or fallbacks (Kumarimall best practice)
const routes = window.routes || {
    store: "/categories",
    update: "/updateCategories/",
    destroy: "/destroyCategories/"
};

if (document.querySelector("#add_categoriesname")) {
    addValidator.addField("#add_categoriesname", [
        { rule: "required", errorMessage: "*Categories Name Field is required" },
        { rule: 'minLength', value: 3, errorMessage: '*Categories Name should be at least 3 character long' },
        { rule: 'maxLength', value: 50, errorMessage: '*Categories Name should be at Maximum 50 character long' },
    ]);
}

if (document.querySelector("#add_categoryImage")) {
    addValidator.addField("#add_categoryImage", [
        {
            rule: 'minFilesCount',
            value: 1,
            errorMessage: '*Category Image is required',
        },
        {
            rule: 'files',
            value: {
                files: {
                    extensions: ['jpeg', 'jpg', 'png', 'webp'],
                    maxSize: 5120000,
                    minSize: 1,
                    types: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                },
            },
            errorMessage: '*Invalid image format or size (max 5MB)',
        },
        {
            validator: (files) => {
                if (!files || files.length === 0) return true;
                const file = files[0];
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = function() {
                        const result = this.width === 512 && this.height === 512;
                        resolve(result);
                    };
                    img.onerror = () => resolve(false);
                    img.src = URL.createObjectURL(file);
                });
            },
            errorMessage: "*Image must be exactly 512x512 pixels"
        }
    ]);
}

addValidator.onSuccess((event) => {
    $(".add_submit_btn").attr("disabled", true).html("Uploading...");
    addCategoriesFormSubmit(event);
});

if (document.querySelector("#edit_categoriesname")) {
    editValidator.addField("#edit_categoriesname", [
        { rule: "required", errorMessage: "*Categories Name Field is required" },
        { rule: 'minLength', value: 3, errorMessage: '*Categories Name should be at least 3 character long' },
        { rule: 'maxLength', value: 50, errorMessage: '*Categories Name should be at Maximum 50 character long' },
    ]);
}

if (document.querySelector("#edit_categoryImage")) {
    editValidator.addField("#edit_categoryImage", [
        {
            rule: 'files',
            value: {
                files: {
                    extensions: ['jpeg', 'jpg', 'png', 'webp'],
                    maxSize: 5120000,
                    types: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                },
            },
            errorMessage: '*Invalid image format or size (max 5MB)',
        },
        {
            validator: (files) => {
                if (!files || files.length === 0) return true;
                const file = files[0];
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = function() {
                        const result = this.width === 512 && this.height === 512;
                        resolve(result);
                    };
                    img.onerror = () => resolve(false);
                    img.src = URL.createObjectURL(file);
                });
            },
            errorMessage: "*Image must be exactly 512x512 pixels"
        }
    ]);
}

editValidator.onSuccess((event) => {
    $(".edit_submit_btn").attr("disabled", true).html("Uploading...");
    editCategoriesFormSubmit(event);
});

const gridWrapper = document.getElementById("table-gridjs");
let gridNew = null;

const renderGrid = (categories) => {
    if (gridNew) {
        gridNew.updateConfig({
            data: categories.map((cat, index) => [
                index + 1,
                cat.category_name,
                cat.image || '/assets/images/placeholder.png',
                null, // Action
                cat.category_id,
                cat.theme_primary_color || '',
                cat.theme_light_color || '',
                cat.theme_bg_overlay || '',
                cat.theme_border_radius || '',
                cat.theme_bg_image || ''
            ])
        }).forceRender();
        return;
    }

    gridNew = new gridjs.Grid({
        columns: [
            "S.NO",
            "Category Name",
            {
                name: "Category Image",
                formatter: (cell) => gridjs.html(`<img src="${cell}" style="width:50px; height:50px; object-fit:cover;" class="rounded">`)
            },
            {
                name: "Action",
                sort: false,
                formatter: (_, row) => {
                    const id = row.cells[4].data;
                    const name = row.cells[1].data;
                    const image = row.cells[2].data;
                    const primaryColor = row.cells[5].data || '';
                    const lightColor = row.cells[6].data || '';
                    const overlay = row.cells[7].data || '';
                    const borderRadius = row.cells[8].data || '';
                    const bgImage = row.cells[9].data || '';
                    return gridjs.html(`
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-soft-primary edit_btn" 
                                data-categoriesid="${id}" 
                                data-categoriesname="${name}" 
                                data-categoriesimage="${image}"
                                data-theme-primary="${primaryColor}"
                                data-theme-light="${lightColor}"
                                data-theme-overlay="${overlay}"
                                data-theme-radius="${borderRadius}"
                                data-theme-bg="${bgImage}"
                                data-bs-toggle="modal" data-bs-target="#editCategoriesModal">
                                <i class="mdi mdi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-soft-danger delete_btn" data-categoriesid="${id}">
                                <i class="mdi mdi-trash-can"></i> Delete
                            </button>
                        </div>
                    `);
                }
            },
            { name: "ID", hidden: true },
            { name: "PrimaryColor", hidden: true },
            { name: "LightColor", hidden: true },
            { name: "Overlay", hidden: true },
            { name: "BorderRadius", hidden: true },
            { name: "BgImage", hidden: true }
        ],
        pagination: { limit: 10 },
        sort: true,
        search: true,
        data: categories.map((cat, index) => [
            index + 1,
            cat.category_name,
            cat.image || '/assets/images/placeholder.png',
            null, // Action dummy
            cat.category_id,
            cat.theme_primary_color || '',
            cat.theme_light_color || '',
            cat.theme_bg_overlay || '',
            cat.theme_border_radius || '',
            cat.theme_bg_image || ''
        ]),
        className: { table: 'table table-bordered mb-0' }
    }).render(gridWrapper);
};

// Initial Load
if (window.categories) {
    renderGrid(window.categories);
}

function addCategoriesFormSubmit(e) {
    const formdata = new FormData(e.target);
    $.ajax({
        url: routes.store,
        method: "POST",
        dataType: "json",
        data: formdata,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            $(".add_submit_btn").removeAttr("disabled").html("Submit");
            $("#addCategoriesForm")[0].reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('addCategoriesModal')).hide();
            renderGrid(response.categories);
            Swal.fire("Added", "Category Added Successfully.", "success");
        },
        error: function (jqXHR) {
            $(".add_submit_btn").removeAttr("disabled").html("Submit");
            Swal.fire("Error", jqXHR.responseJSON?.message || "Failed to add category", "error");
        }
    });
}

function editCategoriesFormSubmit(e) {
    const formdata = new FormData(e.target);
    const id = $("#edit_categories_id").val();
    $.ajax({
        url: routes.update.endsWith('/') ? routes.update + id : routes.update + "/" + id,
        method: "POST",
        dataType: "json",
        data: formdata,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            $("#editCategoriesForm")[0].reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editCategoriesModal')).hide();
            $(".edit_submit_btn").removeAttr("disabled").html("Update");
            Swal.fire("Updated", "Category Updated Successfully.", "success").then(() => {
                window.location.reload();
            });
        },
        error: function (jqXHR) {
            $(".edit_submit_btn").removeAttr("disabled").html("Update");
            Swal.fire("Error", jqXHR.responseJSON?.message || "Failed to update category", "error");
        }
    });
}

$(document).on("click", ".edit_btn", function () {
    const imagePath = $(this).attr("data-categoriesimage");
    $("#edit_categories_id").val($(this).attr("data-categoriesid"));
    $("#edit_categoriesname").val($(this).attr("data-categoriesname"));
    $(".edit_preview_image").attr("src", `${imagePath}`);
    
    // Theme Fields
    $("#edit_theme_primary_color").val($(this).attr("data-theme-primary"));
    $("#edit_theme_light_color").val($(this).attr("data-theme-light"));
    $("#edit_theme_bg_overlay").val($(this).attr("data-theme-overlay"));
    $("#edit_theme_border_radius").val($(this).attr("data-theme-radius")).trigger('change');
    
    const bgImage = $(this).attr("data-theme-bg");
    if (bgImage) {
        $("#edit_theme_bg_image_preview").attr("src", '/uploads/' + bgImage).show();
    } else {
        $("#edit_theme_bg_image_preview").hide();
    }
});

$(document).on("click", ".delete_btn", function () {
    const id = $(this).attr("data-categoriesid");
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: routes.destroy.endsWith('/') ? routes.destroy + id : routes.destroy + "/" + id,
                method: "post",
                dataType: "json",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    renderGrid(response.categories);
                    Swal.fire("Deleted!", "Category deleted successfully.", "success");
                },
                error: function (jqXHR) {
                    Swal.fire("Error", jqXHR.responseJSON?.message || "Deletion failed", "error");
                }
            });
        }
    });
});
