$(document).ready(function () {
    const gridWrapper = document.getElementById("table-gridjs");
    if (!gridWrapper) return;

    const renderTable = (orders) => {
        new gridjs.Grid({
            columns: [
                {
                    name: "Order #",
                    formatter: (cell) => gridjs.html(`<a href="javascript:void(0);" class="text-body fw-bold order-link" data-order-id="${cell.id}" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">${cell.number}</a>`)
                },
                "Customer",
                {
                    name: "Delivered On",
                    formatter: (cell) => cell ? new Date(cell).toLocaleDateString() : 'N/A'
                },
                "Amount",
                {
                    name: "Lead Time",
                    formatter: (cell) => gridjs.html(`<span class="badge bg-soft-info text-info">${cell || 0} Days</span>`)
                },
                {
                    name: "Action",
                    formatter: (_, row) => {
                        const id = row.cells[0].data.id;
                        return gridjs.html(`
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-outline-primary btn-sm view-details" type="button" 
                                    data-order-id="${id}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderDetailsModal">
                                    <i class="bx bx-show me-1"></i>View
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="window.open('/orders/${id}/invoice', '_blank')">
                                    <i class="bx bx-printer"></i>
                                </button>
                            </div>
                        `);
                    }
                }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: orders.map(o => [
                { id: o.id, number: o.order_number || `#${o.id}` },
                o.customer_name,
                o.delivery_date,
                o.formatted_total || `₹${parseFloat(o.total_amount).toFixed(2)}`,
                o.processing_days || 0,
                null
            ]),
            style: { table: { 'white-space': 'nowrap' } },
            className: { table: 'table table-bordered mb-0 align-middle text-center' }
        }).render(gridWrapper);
    };

    if (window.ordersData) {
        renderTable(Array.isArray(window.ordersData) ? window.ordersData : (window.ordersData.data || []));
    }
});
