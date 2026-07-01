<script>
    $(document).ready(function () {
        // Order details modal loading
        $('#orderDetailsModal').on('show.bs.modal', function (event) {
            const $modal = $(this);
            const $button = $(event.relatedTarget);
            const orderId = $button.data('order-id');

            // Set the order ID on the print button for the new window redirect
            $('#printOrderDetails').data('order-id', orderId);

            $modal.find('.modal-title').text(`Order #${orderId} Details`);

            fetch(`{{ url('/orders') }}/${orderId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const order = data.order;
                    let html = `
            <div class="row">
                <div class="col-md-8">
                    <h6 class="fw-bold">📋 Order Information</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p><strong>Order Number:</strong> ${order.order_number || '#' + order.id}</p>
                            <p><strong>Status:</strong> ${order.status_badge}</p>
                            <p><strong>Total Amount:</strong> <span class="text-success fw-bold">${order.formatted_total}</span></p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                            ${order.dispatch_date ? `<p><strong>Dispatched:</strong> ${new Date(order.dispatch_date).toLocaleString()}</p>` : ''}
                            ${order.delivery_date ? `<p><strong>Delivered:</strong> ${new Date(order.delivery_date).toLocaleString()}</p>` : ''}
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">👤 Customer Information</h6>
                            <p><strong>Name:</strong> ${order.customer_name} ${order.user_id ? '<span class="badge bg-success-subtle text-success ms-1 font-size-10"><i class="mdi mdi-account-check"></i> Registered</span>' : '<span class="badge bg-secondary-subtle text-secondary ms-1 font-size-10"><i class="mdi mdi-account-outline"></i> Guest</span>'}</p>
                            <p><strong>Email:</strong> <a href="mailto:${order.customer_email}">${order.customer_email}</a></p>
                            ${order.customer_phone ? `<p><strong>Phone:</strong> <a href="tel:${order.customer_phone}">${order.customer_phone}</a></p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">📍 Shipping Address</h6>
                            <address style="white-space: pre-line;">${order.shipping_address}</address>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold">📊 Order Summary</h6>
                    <div class="card border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Status:</span>
                                <span class="badge bg-info">${order.status}</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar ${order.status_progress_class}" role="progressbar"
                                     style="width: ${order.status_progress}%"></div>
                            </div>
                            <small class="text-muted">${order.status_progress}% Complete</small>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h6 class="fw-bold mb-3">🛒 Order Items</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

                    if (data.formatted_items && data.formatted_items.length > 0) {
                        data.formatted_items.forEach(item => {
                            const itemTotal = (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2);
                            html += `<tr>
                    <td>
                        <div class="fw-semibold">${item.name}</div>
                        <small class="text-muted">${item.type || 'product'}</small>
                    </td>
                    <td class="text-center">${item.quantity}</td>
                    <td class="text-end">₹${parseFloat(item.price).toFixed(2)}</td>
                    <td class="text-end fw-semibold">₹${itemTotal}</td>
                </tr>`;
                        });

                        // Add breakdown rows
                        const shipping = parseFloat(order.shipping_cost || 0);
                        const couponDiscount = parseFloat(order.coupon_discount || 0);
                        const otherDiscount = parseFloat(order.discount_amount || 0);
                        const subtotalValue = data.formatted_items.reduce((sum, item) => sum + (parseFloat(item.price) * parseInt(item.quantity)), 0);
                        
                        html += `
                            <tr class="table-light border-top">
                                <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                <td class="text-end">₹${subtotalValue.toFixed(2)}</td>
                            </tr>
                        `;

                        if (shipping > 0) {
                            html += `
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Shipping Charges:</td>
                                    <td class="text-end">₹${shipping.toFixed(2)}</td>
                                </tr>
                            `;
                        }

                        if (couponDiscount > 0) {
                            html += `
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-danger">Coupon Discount (${order.coupon_code || 'Applied'}):</td>
                                    <td class="text-end text-danger">- ₹${couponDiscount.toFixed(2)}</td>
                                </tr>
                            `;
                        }

                        if (otherDiscount > 0) {
                            html += `
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-danger">Other Discount:</td>
                                    <td class="text-end text-danger">- ₹${otherDiscount.toFixed(2)}</td>
                                </tr>
                            `;
                        }

                        const grandTotalValue = parseFloat(order.total_amount).toFixed(2);
                        html += `<tr class="table-light border-top border-2">
                            <td colspan="3" class="text-end fw-bold fs-5">Grand Total:</td>
                            <td class="text-end fw-bold text-success fs-4">₹${grandTotalValue}</td>
                        </tr>`;
                    } else {
                        html += `<tr><td colspan="4" class="text-center py-4">No items found</td></tr>`;
                    }

                    html += `</tbody></table></div>`;
                    $modal.find('.modal-body').html(html);
                })
                .catch(error => {
                    $modal.find('.modal-body').html(`<div class="alert alert-danger">Error loading details</div>`);
                });
        });

        // Print order functionality
        document.getElementById('printOrderDetails')?.addEventListener('click', function () {
            const orderId = $(this).data('order-id');
            if (orderId) {
                window.open(`{{ url('/orders') }}/${orderId}/invoice`, '_blank');
            }
        });

        // Toast helper
        window.showToast = function(type, message) {
            alert(message); // Fallback if no toast system available
        };
    });
</script>
