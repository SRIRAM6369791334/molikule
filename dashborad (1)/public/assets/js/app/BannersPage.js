const gridNew = new gridjs.Grid({
    columns: [
        "S.No",
        {
            name: "Banner Image",
            formatter: (cell) => gridjs.html(`<img src="${cell}" style="width:120px; height:60px; object-fit:cover;" class="rounded shadow-sm">`)
        },
        {
            name: "Status",
            formatter: (cell) => gridjs.html(`<span class="badge bg-${cell ? 'success' : 'secondary'}">${cell ? 'Active' : 'Hidden'}</span>`)
        },
        {
            name: "Action",
            sort: false,
            formatter: (cell, row) => {
                const id = row.cells[4].data;
                return gridjs.html(`
                    <div class="d-flex gap-1">
                        <a href="/banners/${id}/edit" class="btn btn-sm btn-soft-primary"><i class="mdi mdi-pencil"></i></a>
                        <button class="btn btn-sm btn-soft-danger delete-btn" data-id="${id}"><i class="mdi mdi-trash-can"></i></button>
                    </div>
                `);
            }
        },
        { name: "ID", hidden: true }
    ],
    sort: true,
    data: window.banners.map((b, index) => [
        index + 1,
        b.image_url,
        b.is_active,
        null,
        b.id
    ]),
    className: { table: 'table table-bordered mb-0 align-middle text-center' }
});

gridNew.render(document.getElementById("table-gridjs"));

$(document).on("click", ".delete-btn", function () {
    const id = $(this).data("id");
    Swal.fire({
        title: "Delete banner?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/banners/${id}`,
                method: "DELETE",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: () => {
                    Swal.fire("Deleted!", "Banner removed.", "success").then(() => location.reload());
                }
            });
        }
    });
});
