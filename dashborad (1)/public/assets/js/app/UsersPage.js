$(document).ready(function () {
    const gridWrapper = document.getElementById("table-gridjs");
    if (!gridWrapper) return;

    const routes = window.routes || {
        toggle: "/users",
        delete: "/users",
        view: "/users"
    };

    const renderTable = (users) => {
        new gridjs.Grid({
            columns: [
                {
                    name: "User",
                    formatter: (_, row) => {
                        const name = row.cells[0].data;
                        const email = row.cells[1].data;
                        return gridjs.html(`
                            <div>
                                <h6 class="mb-0">${name}</h6>
                                <small class="text-muted">${email}</small>
                            </div>
                        `);
                    }
                },
                { name: "Email", hidden: true },
                "Phone",
                {
                    name: "Role",
                    formatter: (cell) => {
                        const color = cell === 'admin' ? 'danger' : 'primary';
                        return gridjs.html(`<span class="badge bg-${color}">${cell.toUpperCase()}</span>`);
                    }
                },
                {
                    name: "Status",
                    formatter: (cell, row) => {
                        const id = row.cells[6].data; // Index 6 is ID
                        const color = cell === 'active' ? 'success' : 'danger';
                        return gridjs.html(`
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input status-toggle" type="checkbox" data-id="${id}" ${cell === 'active' ? 'checked' : ''}>
                                <label class="form-check-label badge bg-soft-${color} text-${color}">${cell.toUpperCase()}</label>
                            </div>
                        `);
                    }
                },
                "Joined",
                { name: "ID", hidden: true },
                {
                    name: "Action",
                    formatter: (_, row) => {
                        const id = row.cells[6].data; // Index 6 is ID
                        const viewUrl = routes.view.endsWith('/') ? routes.view + id : routes.view + "/" + id;
                        const editUrl = viewUrl + "/edit";
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="${viewUrl}" class="btn btn-sm btn-soft-info"><i class="bx bx-show"></i></a>
                                <a href="${editUrl}" class="btn btn-sm btn-soft-primary"><i class="bx bx-pencil"></i></a>
                                <button class="btn btn-sm btn-soft-danger delete-user" data-id="${id}"><i class="bx bx-trash"></i></button>
                            </div>
                        `);
                    }
                }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: users.map(u => [
                u.name,
                u.email,
                u.phone || '-',
                u.user_type,
                u.status || 'active',
                u.created_at ? new Date(u.created_at).toLocaleDateString() : 'N/A',
                u.id,
                null
            ]),
            className: { table: 'table table-bordered mb-0 align-middle' }
        }).render(gridWrapper);
    };

    // Initial Load
    if (window.usersData && window.usersData.data) {
        renderTable(window.usersData.data);
    }

    // Status Toggle
    $(document).on('change', '.status-toggle', function () {
        const id = $(this).data('id');
        const $switch = $(this);
        const toggleUrl = routes.toggle.endsWith('/') ? `${routes.toggle}${id}/toggle-status` : `${routes.toggle}/${id}/toggle-status`;
        
        $.ajax({
            url: toggleUrl,
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    $switch.next('label').text(res.status.toUpperCase()).removeClass('bg-soft-success bg-soft-danger text-success text-danger').addClass(`bg-soft-${res.badge_class} text-${res.badge_class}`);
                }
            }
        });
    });

    // Delete User
    $(document).on('click', '.delete-user', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete.endsWith('/') ? routes.delete + id : routes.delete + "/" + id;
        
        Swal.fire({
            title: 'Are you sure?',
            text: "User won't be able to access the system!",
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
