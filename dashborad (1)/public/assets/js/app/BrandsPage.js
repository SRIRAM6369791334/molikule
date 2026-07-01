const addValidator = new JustValidate("#addBrandsForm", {
    validateBeforeSubmitting: true,
});
const editValidator = new JustValidate("#editBrandsForm", {
    validateBeforeSubmitting: true,
});

const routes = window.routes || {
    store: "/brands",
    update: "/updateBrands/",
    destroy: "/destroyBrands/"
};

if (document.querySelector("#add_brandname")) {
    addValidator.addField("#add_brandname", [
        { rule: "required", errorMessage: "*Brand Name Field is required" },
        { rule: 'minLength', value: 2, errorMessage: '*Brand Name should be at least 2 character long' },
    ]);
}

addValidator.onSuccess((event) => {
    $(".add_submit_btn").attr("disabled", true).html("Uploading...");
    addBrandsFormSubmit(event);
});

if (document.querySelector("#edit_brandname")) {
    editValidator.addField("#edit_brandname", [
        { rule: 'minLength', value: 2, errorMessage: '*Brand Name should be at least 2 character long' },
    ]);
}

editValidator.onSuccess((event) => {
    $(".edit_submit_btn").attr("disabled", true).html("Uploading...");
    editBrandsFormSubmit(event);
});

const gridWrapper = document.getElementById("table-gridjs");
let gridNew = null;

const renderGrid = (brands) => {
    if (gridNew) {
        gridNew.updateConfig({
            data: brands.map((brand, index) => [
                index + 1,
                brand.brand_id,
                brand.brand_name,
                brand.logo || '',
                brand.brand_id,
                brand.is_active
            ])
        }).forceRender();
        return;
    }

    gridNew = new gridjs.Grid({
        columns: [
            "S.NO",
            "Brand ID",
            "Brand Name",
            {
                name: "Logo",
                formatter: (cell, row) => {
                    const id = row.cells[4].data;
                    const name = row.cells[2].data;
                    const status = row.cells[5].data;
                    
                    if (!cell || cell === '') {
                        return gridjs.html(`
                            <button class="btn btn-sm btn-soft-success edit_btn" 
                                data-brandid="${id}" 
                                data-brandname="${name}" 
                                data-brandimage=""
                                data-brandstatus="${status}"
                                data-bs-toggle="modal" data-bs-target="#editBrandsModal"
                                style="padding: 4px 8px; font-size: 11px;">
                                <i class="mdi mdi-image-plus me-1"></i>Add Logo
                            </button>
                        `);
                    }
                    return gridjs.html(`<img src="${cell}" style="width:50px; height:50px; object-fit:contain;" class="rounded border">`);
                }
            },
            {
                name: "Action",
                sort: false,
                formatter: (_, row) => {
                    const id = row.cells[4].data;
                    const name = row.cells[2].data;
                    const logo = row.cells[3].data;
                    return gridjs.html(`
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-soft-primary edit_btn" 
                                data-brandid="${id}" 
                                data-brandname="${name}" 
                                data-brandimage="${logo}"
                                data-brandstatus="${row.cells[5].data}"
                                data-bs-toggle="modal" data-bs-target="#editBrandsModal">
                                <i class="mdi mdi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-soft-danger delete_btn" data-brandid="${id}">
                                <i class="mdi mdi-trash-can"></i> Delete
                            </button>
                        </div>
                    `);
                }
            },
            { name: "ID", hidden: true },
            { name: "Status", hidden: true }
        ],
        pagination: { limit: 10 },
        sort: true,
        search: true,
        data: brands.map((brand, index) => [
            index + 1,
            brand.brand_id,
            brand.brand_name,
            brand.logo || '',
            brand.brand_id,
            brand.is_active
        ]),
        className: { table: 'table table-bordered mb-0' }
    }).render(gridWrapper);
};

// Initial Load
if (window.brands) {
    renderGrid(window.brands);
}

function addBrandsFormSubmit(e) {
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
            $("#addBrandsForm")[0].reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('addBrandsModal')).hide();
            renderGrid(response.brands);
            Swal.fire("Added", "Brand Added Successfully.", "success");
        },
        error: function (jqXHR) {
            $(".add_submit_btn").removeAttr("disabled").html("Submit");
            let errorMsg = "Failed to add brand";
            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                errorMsg = Object.values(jqXHR.responseJSON.errors)[0][0];
            } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                errorMsg = jqXHR.responseJSON.message;
            }
            Swal.fire("Error", errorMsg, "error");
        }
    });
}

function editBrandsFormSubmit(e) {
    const formdata = new FormData(e.target);
    const id = $("#edit_brand_id").val();
    $.ajax({
        url: routes.update.endsWith('/') ? routes.update + id : routes.update + "/" + id,
        method: "POST",
        dataType: "json",
        data: formdata,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            $("#editBrandsForm")[0].reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editBrandsModal')).hide();
            renderGrid(response.brands);
            $(".edit_submit_btn").removeAttr("disabled").html("Update");
            Swal.fire("Updated", "Brand Updated Successfully.", "success");
        },
        error: function (jqXHR) {
            $(".edit_submit_btn").removeAttr("disabled").html("Update");
            let errorMsg = "Failed to update brand";
            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                errorMsg = Object.values(jqXHR.responseJSON.errors)[0][0];
            } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                errorMsg = jqXHR.responseJSON.message;
            }
            Swal.fire("Error", errorMsg, "error");
        }
    });
}

$(document).on("click", ".edit_btn", function () {
    const imagePath = $(this).attr("data-brandimage");
    const status = $(this).attr("data-brandstatus");
    
    $("#edit_brand_id").val($(this).attr("data-brandid"));
    $("#edit_brandname").val($(this).attr("data-brandname"));
    $(".edit_preview_image").attr("src", `${imagePath}`);
    
    // Set status checkbox
    if (status == 1 || status == "true" || status === true) {
        $("#editBrandActive").prop("checked", true);
    } else {
        $("#editBrandActive").prop("checked", false);
    }
});

$(document).on("click", ".delete_btn", function () {
    const id = $(this).attr("data-brandid");
    Swal.fire({
        title: "Are you sure?",
        text: "Delete this brand permanently?",
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
                    renderGrid(response.brands);
                    Swal.fire("Deleted!", "Brand deleted successfully.", "success");
                },
                error: function (jqXHR) {
                    Swal.fire("Error", jqXHR.responseJSON?.message || "Deletion failed", "error");
                }
            });
        }
    });
});

// Bulk Upload submit handler
$(document).on("submit", "#bulkUploadBrandsForm", function (e) {
    e.preventDefault();
    const submitBtn = $(".bulk_submit_btn");
    submitBtn.attr("disabled", true).html("Uploading...");

    const formdata = new FormData(this);
    $.ajax({
        url: "/brands/bulk-upload",
        method: "POST",
        dataType: "json",
        data: formdata,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            submitBtn.removeAttr("disabled").html("Start Upload");
            $("#bulkUploadBrandsForm")[0].reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkUploadBrandsModal')).hide();
            
            // Refresh table grid
            renderGrid(response.brands);
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
