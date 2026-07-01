$(document).ready(function () {
    const gridWrapper = document.getElementById("table-gridjs");
    if (!gridWrapper) return;

    const routes = window.routes || {
        toggle: "/pincodes",
        delete: "/pincodes"
    };

    const renderTable = (pincodes) => {
        new gridjs.Grid({
            columns: [
                {
                    name: "Pincode",
                    formatter: (cell) => gridjs.html(`<span class="fw-bold">${cell}</span>`)
                },
                "City",
                "State",
                {
                    name: "COD Charge",
                    formatter: (cell) => `₹${parseFloat(cell).toFixed(2)}`
                },
                {
                    name: "Status",
                    formatter: (cell, row) => {
                        const id = row.cells[5].data;
                        const isActive = cell === 1 || cell === true || cell === "1" || cell === "active";
                        const color = isActive ? 'success' : 'danger';
                        return gridjs.html(`
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input pincode-status-toggle" type="checkbox" data-id="${id}" ${isActive ? 'checked' : ''}>
                                <label class="form-check-label badge bg-soft-${color} text-${color}">${isActive ? 'ACTIVE' : 'INACTIVE'}</label>
                            </div>
                        `);
                    }
                },
                { name: "ID", hidden: true },
                {
                    name: "Action",
                    formatter: (_, row) => {
                        const id = row.cells[5].data;
                        const editUrl = routes.delete.endsWith('/') ? `${routes.delete}${id}/edit` : `${routes.delete}/${id}/edit`;
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="${editUrl}" class="btn btn-sm btn-soft-primary"><i class="bx bx-pencil"></i></a>
                                <button class="btn btn-sm btn-soft-danger delete-pincode" data-id="${id}"><i class="bx bx-trash"></i></button>
                            </div>
                        `);
                    }
                }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: pincodes.map(p => [
                p.pincode,
                p.city,
                p.state,
                p.cod_charge || 0,
                p.is_active,
                p.id,
                null
            ]),
            className: { table: 'table table-bordered mb-0 align-middle' }
        }).render(gridWrapper);
    };

    // Initial Load
    if (window.pincodesData && window.pincodesData.data) {
        renderTable(window.pincodesData.data);
    }

    // Status Toggle
    $(document).on('change', '.pincode-status-toggle', function () {
        const id = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;
        const $switch = $(this);
        const toggleUrl = routes.toggle.endsWith('/') ? `${routes.toggle}${id}/toggle-status` : `${routes.toggle}/${id}/toggle-status`;

        $.ajax({
            url: toggleUrl,
            method: 'POST',
            data: { is_active: isActive },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    const color = res.is_active ? 'success' : 'danger';
                    const text = res.is_active ? 'ACTIVE' : 'INACTIVE';
                    $switch.next('label').text(text).removeClass('bg-soft-success bg-soft-danger text-success text-danger').addClass(`bg-soft-${color} text-${color}`);
                }
            }
        });
    });

    // Delete Pincode
    $(document).on('click', '.delete-pincode', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete.endsWith('/') ? routes.delete + id : routes.delete + "/" + id;

        Swal.fire({
            title: 'Delete Pincode?',
            text: "This service area will be disabled!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = deleteUrl;
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
