$(document).ready(function () {
    const gridWrapper = document.getElementById("table-gridjs");
    if (!gridWrapper) return;

    const routes = window.routes || {
        delete: "/orders",
        updateStatus: "/orders"
    };

    const statusMap = {
        'pending': { label: 'Pending', color: 'warning', next: 'processing' },
        'processing': { label: 'Processing', color: 'secondary', next: 'dispatch' },
        'dispatch': { label: 'Dispatched', color: 'info', next: 'delivered' },
        'delivered': { label: 'Delivered', color: 'success', next: null }
    };

    const renderTable = (orders) => {
        new gridjs.Grid({
            columns: [
                {
                    name: "Order #",
                    formatter: (cell) => gridjs.html(`<a href="javascript:void(0);" class="text-body fw-bold order-link" data-order-id="${cell.id}" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">${cell.number}</a>`)
                },
                "Customer",
                "Date",
                "Amount",
                {
                    name: "Status",
                    formatter: (_, row) => {
                        const currentStatus = row.cells[6].data; // Hidden Status Raw
                        const id = row.cells[0].data.id;
                        const version = row.cells[7].data; // Hidden Version
                        const config = statusMap[currentStatus] || { label: currentStatus, color: 'secondary', next: null };
                        
                        // If it's delivered, just show badge
                        if (!config.next) {
                            return gridjs.html(`<span class="badge bg-success rounded-pill px-3"><i class="bx bx-check-double me-1"></i>Delivered</span>`);
                        }

                        // Otherwise show a "Change to Next" dropdown or selector
                        return gridjs.html(`
                            <div class="status-selector-container">
                                <select class="form-select form-select-sm status-inline-change bg-soft-${config.color} text-${config.color} border-${config.color}" 
                                    data-id="${id}" 
                                    data-version="${version}"
                                    style="width: 140px; font-weight: 600;">
                                    <option value="${currentStatus}" selected>${config.label.toUpperCase()}</option>
                                    <option value="${config.next}">MOVE TO ${statusMap[config.next].label.toUpperCase()}</option>
                                </select>
                            </div>
                        `);
                    }
                },
                {
                    name: "Action",
                    formatter: (_, row) => {
                        const id = row.cells[0].data.id;
                        const number = row.cells[0].data.number;
                        return gridjs.html(`
                            <button class="btn btn-outline-primary btn-sm view-details" type="button" 
                                data-order-id="${id}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#orderDetailsModal">
                                <i class="bx bx-show me-1"></i>View
                            </button>
                        `);
                    }
                },
                { name: "RawStatus", hidden: true },
                { name: "Version", hidden: true }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: orders.map(o => [
                { id: o.id, number: o.order_number || `#${o.id}` },
                o.customer_name,
                o.created_at ? new Date(o.created_at).toLocaleDateString() : 'N/A',
                o.formatted_total || `₹${parseFloat(o.total_amount).toFixed(2)}`,
                null,
                null,
                o.status,
                o.version || 1
            ]),
            style: { table: { 'white-space': 'nowrap' } },
            className: { table: 'table table-bordered mb-0 align-middle text-center' }
        }).render(gridWrapper);
    };

    // Initial Load
    if (window.ordersData && window.ordersData.data) {
        renderTable(window.ordersData.data);
    }

    // Inline Status Change Handler
    $(document).on('change', '.status-inline-change', function() {
        const id = $(this).data('id');
        const version = $(this).data('version');
        const newStatus = $(this).val();
        const $select = $(this);
        const originalStatus = $select.find('option[selected]').val();

        if (newStatus === originalStatus) return;

        const updateUrl = routes.updateStatus.endsWith('/') ? `${routes.updateStatus}${id}/update-status` : `${routes.updateStatus}/${id}/update-status`;

        Swal.fire({
            title: 'Change Order Status?',
            text: `Confirm update to ${newStatus.toUpperCase()}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: updateUrl,
                    method: 'POST',
                    data: {
                        status: newStatus,
                        version: version,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Updated!', res.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            $select.val(originalStatus);
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function(jqXHR) {
                        $select.val(originalStatus);
                        const msg = jqXHR.responseJSON?.message || 'Update failed';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            } else {
                $select.val(originalStatus);
            }
        });
    });

    // Delete Order Handler
    $(document).on('click', '.delete-order-btn', function() {
        const id = $(this).data('id');
        const number = $(this).data('number');
        const deleteUrl = routes.delete.endsWith('/') ? routes.delete + id : routes.delete + "/" + id;
        
        Swal.fire({
            title: 'Delete Order?',
            text: `Are you sure you want to delete order ${number}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f46a6a',
            confirmButtonText: 'Yes, delete it!'
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
