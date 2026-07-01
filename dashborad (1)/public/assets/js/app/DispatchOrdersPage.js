$(document).ready(function () {
    const gridWrapper = document.getElementById("table-gridjs");
    if (!gridWrapper) return;

    const routes = window.routes || {
        delete: "/orders",
        updateStatus: "/orders"
    };

    const renderTable = (orders) => {
        new gridjs.Grid({
            columns: [
                {
                    name: "Order #",
                    formatter: (cell) => gridjs.html(`<a href="javascript:void(0);" class="text-body fw-bold order-link" data-order-id="${cell.id}" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">${cell.number}</a>`)
                },
                "Customer",
                {
                    name: "Dispatched On",
                    formatter: (cell) => cell ? new Date(cell).toLocaleDateString() : 'N/A'
                },
                "Amount",
                {
                    name: "Status",
                    formatter: (_, row) => {
                        const id = row.cells[0].data.id;
                        const version = row.cells[6].data;
                        return gridjs.html(`
                            <div class="status-selector-container">
                                <select class="form-select form-select-sm status-inline-change bg-soft-info text-info border-info" 
                                    data-id="${id}" 
                                    data-version="${version}"
                                    style="width: 140px; font-weight: 600;">
                                    <option value="dispatch" selected>DISPATCHED</option>
                                    <option value="delivered">MARK DELIVERED</option>
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
                { name: "Version", hidden: true }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: orders.map(o => [
                { id: o.id, number: o.order_number || `#${o.id}` },
                o.customer_name,
                o.dispatch_date,
                o.formatted_total || `₹${parseFloat(o.total_amount).toFixed(2)}`,
                null,
                null,
                o.version || 1
            ]),
            style: { table: { 'white-space': 'nowrap' } },
            className: { table: 'table table-bordered mb-0 align-middle text-center' }
        }).render(gridWrapper);
    };

    if (window.ordersData) {
        renderTable(Array.isArray(window.ordersData) ? window.ordersData : (window.ordersData.data || []));
    }

    // Reuse common handlers (can be refactored to shared utility)
    $(document).on('change', '.status-inline-change', function() {
        const id = $(this).data('id');
        const version = $(this).data('version');
        const newStatus = $(this).val();
        const $select = $(this);

        if (newStatus === 'dispatch') return;

        Swal.fire({
            title: 'Confirm Delivery',
            text: "Are you sure this order has reached the customer?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Mark Delivered'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${routes.updateStatus}/${id}/update-status`,
                    method: 'POST',
                    data: { status: newStatus, version: version, _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Fulfilled!', res.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        }
                    }
                });
            } else {
                $select.val('dispatch');
            }
        });
    });
});
