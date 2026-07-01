$(document).ready(function () {
    const gridWrapper = document.getElementById("table-gridjs");
    if (!gridWrapper) return;

    const routes = window.routes || {
        toggle: "/blogs",
        delete: "/blogs"
    };

    const renderTable = (blogs) => {
        new gridjs.Grid({
            columns: [
                {
                    name: "Content",
                    formatter: (_, row) => {
                        const title = row.cells[0]?.data || 'No Title';
                        const image = row.cells[1]?.data || '/assets/images/placeholder.jpg';
                        const slug = row.cells[2]?.data || '';
                        return gridjs.html(`
                            <div class="d-flex align-items-center">
                                <img src="${image}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">${title}</h6>
                                    
                                </div>
                            </div>
                        `);
                    }
                },
                { name: "Image", hidden: true },
                { name: "Slug", hidden: true },
                {
                    name: "Category",
                    formatter: (cell) => gridjs.html(`<span class="badge bg-soft-info text-info">${cell}</span>`)
                },
                "Author",
                {
                    name: "Status",
                    formatter: (cell, row) => {
                        const id = row.cells[6]?.data;
                        const isPublished = cell === 1 || cell === true || cell === "1";
                        const color = isPublished ? 'success' : 'warning';
                        return gridjs.html(`
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input blog-status-toggle" type="checkbox" data-id="${id}" ${isPublished ? 'checked' : ''}>
                                <label class="form-check-label badge bg-soft-${color} text-${color}">${isPublished ? 'PUBLISHED' : 'DRAFT'}</label>
                            </div>
                        `);
                    }
                },
                { name: "ID", hidden: true },
                {
                    name: "Action",
                    formatter: (_, row) => {
                        const id = row.cells[6]?.data;
                        const editUrl = routes.delete.endsWith('/') ? `${routes.delete}${id}/edit` : `${routes.delete}/${id}/edit`;
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="${editUrl}" class="btn btn-sm btn-soft-primary"><i class="bx bx-pencil"></i></a>
                                <button class="btn btn-sm btn-soft-danger delete-blog-btn" data-id="${id}"><i class="bx bx-trash"></i></button>
                            </div>
                        `);
                    }
                }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: blogs.map(b => [
                b.title || 'No Title',
                b.image_full_url || '/assets/images/placeholder.jpg',
                b.slug || '',
                b.category ? b.category.name : 'Uncategorized',
                b.author ? b.author.name : 'Unknown',
                b.is_published,
                b.id,
                null
            ]),
            className: { table: 'table table-bordered mb-0 align-middle' }
        }).render(gridWrapper);
    };

    // Initial Load
    if (window.blogsData && window.blogsData.data) {
        renderTable(window.blogsData.data);
    }

    // Status Toggle
    $(document).on('change', '.blog-status-toggle', function () {
        const id = $(this).data('id');
        const $switch = $(this);
        const toggleUrl = routes.toggle.endsWith('/') ? `${routes.toggle}${id}/toggle-status` : `${routes.toggle}/${id}/toggle-status`;

        $.ajax({
            url: toggleUrl,
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    const color = res.is_published ? 'success' : 'warning';
                    const text = res.is_published ? 'PUBLISHED' : 'DRAFT';
                    $switch.next('label').text(text).removeClass('bg-soft-success bg-soft-warning text-success text-warning').addClass(`bg-soft-${color} text-${color}`);
                }
            }
        });
    });

    // Delete Blog
    $(document).on('click', '.delete-blog-btn', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete.endsWith('/') ? routes.delete + id : routes.delete + "/" + id;

        Swal.fire({
            title: 'Delete Post?',
            text: "This blog post will be permanently removed!",
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
