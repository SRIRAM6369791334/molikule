$(document).ready(function () {
    let gridInstance = null;
    let currentFilter = 'all';

    function initGrid(filter = 'all') {
        $.getJSON(stockEndpoint + '?filter=' + filter, function (variants) {
            if (gridInstance) {
                gridInstance.destroy();
            }

            gridInstance = new gridjs.Grid({
                columns: [
                    "#",
                    {
                        name: "Product/Variant",
                        formatter: (cell) => gridjs.html(`<div class="d-flex align-items-center"><img src="${cell.image}" class="rounded me-2" style="width:35px;height:35px;object-fit:cover;"><span>${cell.name}</span></div>`)
                    },
                    "Category",
                    {
                        name: "Sold",
                        formatter: (cell) => gridjs.html(`<div class="text-center"><span class="badge bg-soft-info text-info px-3"><strong>${cell}</strong> Sold</span></div>`)
                    },
                    {
                        name: "Available",
                        formatter: (cell) => {
                            const count = parseInt(cell);
                            let color = "success";
                            if (count <= 10) color = "warning";
                            if (count <= 5) color = "danger";
                            if (count === 0) color = "dark";
                            return gridjs.html(`<div class="text-center"><span class="badge bg-soft-${color} text-${color} px-3"><strong>${count}</strong> In Stock</span></div>`);
                        }
                    },
                    {
                        name: "Actions",
                        sort: false,
                        formatter: (_, row) => {
                            const data = {
                                id: row.cells[7].data,
                                name: row.cells[1].data.name,
                                current_qty: row.cells[4].data
                            };
                            return gridjs.html(`
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success btn-stock-adj" data-type="add" data-id="${data.id}" data-name="${data.name}" data-qty="${data.current_qty}">
                                        <i class="mdi mdi-plus"></i> Add
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-stock-adj" data-type="reduce" data-id="${data.id}" data-name="${data.name}" data-qty="${data.current_qty}">
                                        <i class="mdi mdi-minus"></i> Reduce
                                    </button>
                                </div>
                            `);
                        }
                    },
                    { name: "ID", hidden: true },
                    { name: "RawID", hidden: true }
                ],
                pagination: { limit: 12 },
                sort: true,
                search: true,
                data: variants.map((v, index) => [
                    index + 1,
                    { name: v.name, image: v.image },
                    v.category,
                    v.sold,
                    v.available,
                    null, // Actions placeholder
                    v.id, // ID
                    v.id  // RawID
                ]),
                autoWidth: true,
                className: { table: 'table table-bordered mb-0 align-middle' }
            }).render(document.getElementById("table-stock-management"));
        });
    }

    initGrid();

    // Event Delegation for Adjustment Buttons
    $(document).on('click', '.btn-stock-adj', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');
        const name = $(this).data('name');
        const qty = $(this).data('qty');

        $('#adj_variant_id').val(id);
        $('#adj_type').val(type);
        $('#adj_product_name').text(name);
        $('#adj_current_qty').text(qty);

        if(type === 'add') {
            $('#modalTitle').text('Restock Inventory');
            $('#adj_action_text').text('add');
            $('#adj_icon').removeClass('bg-soft-danger text-danger').addClass('bg-soft-primary text-primary').find('i').removeClass('mdi-minus').addClass('mdi-plus');
            $('#submitBtn').removeClass('btn-danger').addClass('btn-primary').text('Add to Stock');
        } else {
            $('#modalTitle').text('Reduce Inventory');
            $('#adj_action_text').text('subtract');
            $('#adj_icon').removeClass('bg-soft-primary text-primary').addClass('bg-soft-danger text-danger').find('i').removeClass('mdi-plus').addClass('mdi-minus');
            $('#submitBtn').removeClass('btn-primary').addClass('btn-danger').text('Reduce Stock');
        }

        $('#stockAdjustmentModal').modal('show');
    });

    // Handle Filter Clicks
    $(document).on('click', '.filter-btn', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        initGrid(currentFilter);
    });

    // Handle Form Submission
    $('#stockAdjustmentForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#submitBtn');
        const originalText = btn.text();
        
        btn.prop('disabled', true).text('Updating...');

        $.ajax({
            url: updateStockEndpoint,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#stockAdjustmentModal').modal('hide');
                    $('#stockAdjustmentForm')[0].reset();
                    initGrid(currentFilter); // Refresh table with current filter
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text(originalText);
            }
        });
    });
});
