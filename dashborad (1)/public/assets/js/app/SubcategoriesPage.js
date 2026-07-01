const gridNew = new gridjs.Grid({
    columns: [
        "S.NO",
        {
            name: "Sub Category",
            formatter: (cell, row) => {
                return gridjs.html(`
                    <div class="d-flex align-items-center gap-2">
                        <img src="${row.cells[2].data}" style="width:40px; height:40px; object-fit:cover;" class="rounded border">
                        <div>
                            <h6 class="mb-0 font-size-14">${cell}</h6>
                        </div>
                    </div>
                `);
            }
        },
        { name: "Image", hidden: true },
        "Parent Category",
        {
            name: "Status",
            formatter: (cell) => gridjs.html(`<span class="badge bg-${cell ? 'success' : 'secondary'}">${cell ? 'Active' : 'Hidden'}</span>`)
        },
        {
            name: "Action",
            sort: false,
            formatter: (cell, row) => {
                const id = row.cells[6].data;
                const data = JSON.stringify({
                    id: id,
                    name: row.cells[1].data,
                    parent_id: row.cells[7].data,
                    status: row.cells[4].data === 'Active' ? 1 : 0
                }).replace(/"/g, '&quot;');

                return gridjs.html(`
                    <div class="d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-soft-primary edit-btn" data-category="${data}"><i class="mdi mdi-pencil"></i></button>
                        <button class="btn btn-sm btn-soft-danger delete-btn" data-id="${id}"><i class="mdi mdi-trash-can"></i></button>
                    </div>
                `);
            }
        },
        { name: "ID", hidden: true },
        { name: "ParentID", hidden: true }
    ],
    pagination: { limit: 10 },
    sort: true,
    search: true,
    data: subcategories.map((s, index) => [
        index + 1,
        s.category_name,
        s.category_image_url || 'categories/default.png',
        s.parent ? s.parent.category_name : 'No Parent',
        s.active,
        null,
        s.category_id,
        s.parent_id
    ]),
    className: { table: 'table table-bordered mb-0 align-middle text-center' }
});

gridNew.render(document.getElementById("table-gridjs"));

// Re-use logic from CategoriesPage for Create/Update/Delete (with parent_id support)
const validator = window.JustValidate('#subcategoryForm');
validator
    .addField('#category_name', [{ rule: 'required', errorMessage: 'Name is required' }])
    .addField('#parent_id', [{ rule: 'required', errorMessage: 'Please select a parent category' }])
    .onSuccess((event) => {
        const formData = new FormData(event.target);
        const id = $('#category_id').val();
        const url = id ? `/categories/${id}` : '/categories';
        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: (response) => {
                Swal.fire('Success', response.message, 'success').then(() => location.reload());
            },
            error: (err) => {
                Swal.fire('Error', err.responseJSON.message || 'Operation failed', 'error');
            }
        });
    });

$(document).on("click", ".edit-btn", function () {
    const data = JSON.parse($(this).attr("data-category").replace(/&quot;/g, '"'));
    $("#category_id").val(data.id);
    $("#category_name").val(data.name);
    $("#parent_id").val(data.parent_id).trigger('change');
    $("#active").val(data.status);
    $("#modalTitle").text("Edit Subcategory");
    $("#subcategoryModal").modal('show');
});

$("#addBtn").click(() => {
    $("#subcategoryForm")[0].reset();
    $("#category_id").val('');
    $("#modalTitle").text("Add New Subcategory");
    $("#subcategoryModal").modal('show');
});

$(document).on("click", ".delete-btn", function () {
    const id = $(this).data("id");
    Swal.fire({
        title: "Delete Subcategory?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/categories/${id}`,
                method: "DELETE",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: (response) => {
                    Swal.fire("Deleted!", "Subcategory has been removed.", "success").then(() => location.reload());
                }
            });
        }
    });
});
