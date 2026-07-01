$(document).ready(function () {
    $.getJSON(endpoint, function (products) {
        new gridjs.Grid({
            columns: [
                "#",
                {
                    name: "Product",
                    formatter: (cell) => gridjs.html(`<div class="d-flex align-items-center"><img src="${cell.image}" class="rounded me-2" style="width:35px;height:35px;object-fit:cover;"><span>${cell.name}</span></div>`)
                },
                "Category",
                "Brand",
                {
                    name: "Stock Status",
                    formatter: (cell) => {
                        const count = parseInt(cell);
                        let color = "danger";
                        let text = "Critical";
                        
                        if (count > 5) { 
                            color = "warning"; 
                            text = "Low"; 
                        } else if (count <= 0) { 
                            color = "dark"; 
                            text = "Out of Stock"; 
                        }
                        
                        const displayBadge = count < 0 
                            ? `<span class="badge bg-soft-danger text-danger px-3 py-2"><strong>${count}</strong> - Negative Stock</span>`
                            : `<span class="badge bg-soft-${color} text-${color} px-3 py-2"><strong>${count}</strong> - ${text}</span>`;
                            
                        return gridjs.html(displayBadge);
                    }
                },
                {
                    name: "Quick Action",
                    sort: false,
                    formatter: (_, row) => {
                        const id = row.cells[5].data;
                        return gridjs.html(`
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="/products/${id}/edit" class="btn btn-sm btn-soft-primary" title="Update Stock"><i class="mdi mdi-plus-box"></i> Restock</a>
                            </div>
                        `);
                    }
                },
                { name: "ID", hidden: true }
            ],
            pagination: { limit: 10 },
            sort: true,
            search: true,
            data: products.map((p, index) => [
                index + 1,
                { name: p.name, image: p.image || 'products/default.png' },
                p.category ? p.category.category_name : 'N/A',
                p.brand ? p.brand.brand_name : 'N/A',
                p.stock_quantity,
                p.product_id
            ]),
            autoWidth: true,
            className: { table: 'table table-bordered mb-0 align-middle' }
        }).render(document.getElementById("table-low-stock"));
    });
});
