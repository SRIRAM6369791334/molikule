<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateOrderStatus;
use App\Mail\OrderPending;
use App\Mail\OrderProcessing;
use App\Mail\OrderDispatched;
use App\Mail\OrderDelivered;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pincode; // Added missing use statement for Pincode
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected OrderService $orderService; // Added type hint

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of all orders, with filters.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'customer', 'order_number', 'start_date', 'end_date']);
        $orders = $this->orderService->getFilteredOrders($filters, 50);
        $stats = $this->orderService->getOrderStats();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'orders' => $orders,
                'stats' => $stats
            ]);
        }

        return view('orders.all', compact('orders', 'stats'));
    }

    // Specific status views (kept as-is, though they could be refactored into a single method)
    public function pending(Request $request)
    {
        $filters = [
            'status' => Order::STATUS_PENDING,
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'desc')
        ];

        $orders = $this->orderService->getFilteredOrders($filters);
        $stats = $this->orderService->getOrderStats();

        return view('orders.all', compact('orders', 'stats')); 
    }

    public function processing(Request $request)
    {
        $filters = [
            'status' => Order::STATUS_PROCESSING,
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'desc')
        ];

        $orders = $this->orderService->getFilteredOrders($filters);
        $stats = $this->orderService->getOrderStats();

        return view('orders.all', compact('orders', 'stats'));
    }

    public function dispatched(Request $request)
    {
        $filters = [
            'status' => Order::STATUS_DISPATCH,
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'desc')
        ];

        $orders = $this->orderService->getFilteredOrders($filters);
        $stats = $this->orderService->getOrderStats();

        return view('orders.dispatch', compact('orders', 'stats'));
    }

    public function delivered(Request $request)
    {
        $filters = [
            'status' => Order::STATUS_DELIVERY,
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'desc')
        ];

        $orders = $this->orderService->getFilteredOrders($filters);
        $stats = $this->orderService->getOrderStats();

        return view('orders.deliver', compact('orders', 'stats'));
    }

    /**
     * Display orders created today.
     */
    public function todayOrders(Request $request)
    {
        $filters = [
            'today' => true,
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'desc')
        ];

        $orders = $this->orderService->getFilteredOrders($filters);
        $stats = $this->orderService->getOrderStats();

        return view('orders.today', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'pincode' => 'required|string|max:10',
            'shipping_address' => 'required|string|max:1000',
            'billing_address' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string|max:50',
            'coupon_discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            if (!Pincode::isServiceable($validated['pincode'])) { 
                return redirect()->back()
                             ->with('error', 'Delivery is not available for the entered pincode. Please check and update your delivery areas.')
                             ->withInput();
            }

            $orderNumber = Order::generateOrderNumber();

            // Calculate subtotal from items
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            // Calculate final total amount
            $shippingCost = $validated['shipping_cost'] ?? 0;
            $discount = ($validated['discount_amount'] ?? 0) + ($validated['coupon_discount'] ?? 0);
            $totalAmount = ($subtotal + $shippingCost) - $discount;

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'pincode' => $validated['pincode'],
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'] ?: $validated['shipping_address'], 
                'shipping_cost' => $shippingCost,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'coupon_code' => $validated['coupon_code'] ?? null,
                'coupon_discount' => $validated['coupon_discount'] ?? 0,
                'total_amount' => max(0, $totalAmount),
                'status' => Order::STATUS_PENDING,
                'notes' => $validated['notes'] ?? null
            ]);

            // --- RECORD COUPON USAGE ---
            if (!empty($validated['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])->first();
                if ($coupon) {
                    \App\Models\CouponUsage::create([
                        'coupon_id'      => $coupon->id,
                        'user_id'        => auth()->id(),
                        'order_id'       => $order->id,
                        'customer_email' => $validated['customer_email'],
                        'created_at'     => now(),
                    ]);
                }
            }

            // Create order items for each item in the order
            $stockDecrements = []; // Track stock changes for rollback if needed
            
            foreach ($validated['items'] as $itemData) {
                // Use lockForUpdate to ensure atomic check and decrement
                $product = Product::where('product_id', $itemData['product_id'])->lockForUpdate()->first();

                if (!$product || !$product->active || $product->mrp_price != $itemData['price']) {
                    throw new \Exception("Product not found, inactive, or price mismatch: {$itemData['product_id']}");
                }

                // Check stock availability (protected by lock)
                if ($product->stock_quantity < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}. Requested: {$itemData['quantity']}, Available: {$product->stock_quantity}");
                }

                // Reduce stock quantity using model save to trigger safety hooks
                $product->stock_quantity -= $itemData['quantity'];
                $product->save();

                // Log Inventory Transaction
                \App\Models\InventoryTransaction::create([
                    'product_id' => $product->product_id,
                    'type' => 'order',
                    'quantity' => -$itemData['quantity'],
                    'reference_id' => $order->id,
                    'note' => 'Order #' . $order->order_number,
                    'created_by' => auth()->id()
                ]);

                // Clear product caches after stock change
                $productService = app(\App\Services\ProductService::class);
                $productService->clearProductCaches($product->product_id);
                $productService->clearProductListCaches();

                $stockDecrements[] = ['product_id' => $product->product_id, 'quantity' => $itemData['quantity']];

                OrderItem::create([
                    'order_id' => $order->id,
                    'itemable_type' => Product::class,
                    'itemable_id' => $product->product_id, // Ensure this matches the product's primary key name
                    'item_name' => $product->name,
                    'unit_price' => $itemData['price'],
                    'quantity' => $itemData['quantity'],
                    'total_price' => $itemData['price'] * $itemData['quantity']
                ]);
            }

            DB::commit();

            $order->load('orderItems');
            $adminEmail = env('ADMIN_EMAIL');

            if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($order->customer_email)->queue(new OrderPending($order));
            } else {
                Log::warning("Invalid customer email for order {$order->order_number}. Skipping customer email.");
            }

            if (filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($adminEmail)->queue(new OrderPending($order));
            } else {
                Log::warning("Invalid or missing admin email. Skipping admin email for order {$order->order_number}.");
            }
            Log::info("Order pending emails queued for order {$order->order_number}.");

            return redirect()->route('orders.index')
                             ->with('success', 'Order created successfully! Order #' . $orderNumber);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Order creation failed: " . $e->getMessage()); // Added logging
            return redirect()->back()
                             ->with('error', 'Failed to create order: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Eager load relationships to prevent N+1 queries
        $order->load(['user', 'orderItems.itemable']);

        if (request()->expectsJson()) {
            // Return JSON with items relationship
            $orderData = $order->toArray();
            $orderData['items'] = $order->orderItems->map(function ($item) {
                return [
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'itemable_type' => $item->itemable_type,
                    'itemable_id' => $item->itemable_id
                ];
            })->toArray();
            return response()->json($orderData);
        }
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $products = Product::active()->orderBy('name')->get();
        return view('orders.edit', compact('order', 'products'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'pincode' => 'required|string|max:10',
            'shipping_address' => 'required|string|max:1000',
            'billing_address' => 'nullable|string|max:1000',
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUS_LABELS)),
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($order->status === Order::STATUS_DELIVERY && $validated['status'] !== Order::STATUS_DELIVERY) { 
            return response()->json(['success' => false, 'message' => 'Cannot change status of delivered orders'], 400);
        }

        $order->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        }

        return redirect()->back()->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return response()->json(['success' => false, 'message' => 'Can only delete pending orders'], 400);
        }

        DB::beginTransaction();
        try {
            // Restore stock quantities for all order items
            $productService = app(\App\Services\ProductService::class);
            foreach ($order->orderItems as $item) {
                if ($item->itemable_type === Product::class) {
                    $product = Product::find($item->itemable_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);

                        // Clear product caches after stock change
                        $productService->clearProductCaches($product->product_id);
                        $productService->clearProductListCaches();
                    }
                } elseif ($item->itemable_type === ProductVariant::class) {
                    $variant = ProductVariant::find($item->itemable_id);
                    if ($variant) {
                        $variant->increment('stock_quantity', $item->quantity);

                        // Clear variant caches if needed (not implemented yet)
                    }
                }
            }

            $order->delete();
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
            }

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order deletion failed: " . $e->getMessage()); // Added logging
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete order'], 500);
            }
            return redirect()->back()->with('error', 'Failed to delete order');
        }
    }

    /**
     * Enhanced status update with workflow validation and immediate processing.
     * Changed from queued to synchronous for immediate feedback.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUS_LABELS)),
            'version' => 'required|integer|min:1' // Add version validation for optimistic locking
        ]);

        Log::info("Status update requested for order {$order->order_number}: from {$order->status} to {$validated['status']} (version: {$validated['version']})");

        // Check optimistic locking version (cast to int to ensure type matching)
        if ((int)$order->version !== (int)$validated['version']) {
            Log::warning("Version conflict for order {$order->order_number}: expected {$validated['version']}, got {$order->version}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order has been modified by another user. Please refresh the page and try again.',
                    'error_type' => 'version_conflict'
                ], 409);
            } else {
                return back()->with('error', 'This order has been modified by another user. Please refresh the page and try again.')->withInput();
            }
        }

        // Validate status transition using the model's workflow
        if (!$order->canTransitionTo($validated['status'])) {
            Log::warning("Invalid status transition for order {$order->order_number}: from {$order->status} to {$validated['status']}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition. Cannot change from ' . $order->status_label . ' to ' . (Order::STATUS_LABELS[$validated['status']] ?? ucfirst($validated['status']))
                ], 400);
            } else {
                return back()->with('error', 'Invalid status transition. Cannot change from ' . $order->status_label . ' to ' . (Order::STATUS_LABELS[$validated['status']] ?? ucfirst($validated['status'])))->withInput();
            }
        }

        // Prepare metadata
        $metadata = [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'timestamp' => now()->toISOString()
        ];

        // Store old status for event
        $oldStatus = $order->status;

        // Perform the status transition immediately (synchronous)
        if (!$order->transitionTo($validated['status'], auth()->id(), null, $metadata)) {
            Log::error("Failed to transition order {$order->order_number} to status {$validated['status']}");
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update order status. Please try again.'
                ], 500);
            } else {
                return back()->with('error', 'Failed to update order status. Please try again.');
            }
        }

        Log::info("Status transition successful for order {$order->order_number} to {$validated['status']}");

        // Fire event for notifications and broadcasting
        // This will trigger email notifications via the OrderStatusUpdatedListener
        event(new \App\Events\OrderStatusUpdated($order, $oldStatus, $validated['status'], auth()->id(), $metadata));

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully to ' . $order->status_label,
                'new_version' => $order->version,
                'status_badge' => $order->status_badge
            ]);
        } else {
            return back()->with('success', 'Order status updated successfully to ' . $order->status_label);
        }
    }

    /**
     * Bulk status updates with immediate processing.
     * Changed from queued to synchronous for immediate feedback.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id',
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUS_LABELS))
        ]);

        $orders = Order::whereIn('id', $validated['order_ids'])->get();
        $updated = 0;
        $skipped = 0;

        foreach ($orders as $order) {
            /** @var Order $order */
            // Skip if transition is not valid
            if (!$order->canTransitionTo($validated['status'])) {
                Log::warning("Skipping invalid bulk transition for order {$order->order_number}: {$order->status} -> {$validated['status']}");
                $skipped++;
                continue;
            }

            // Prepare metadata
            $metadata = [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'request_method' => request()->method(),
                'timestamp' => now()->toISOString(),
                'bulk_operation' => true
            ];

            // Store old status for event
            $oldStatus = $order->status;

            // Perform the status transition immediately
            if ($order->transitionTo($validated['status'], auth()->id(), 'Bulk status update', $metadata)) {
                // Fire event for notifications
                event(new \App\Events\OrderStatusUpdated($order, $oldStatus, $validated['status'], auth()->id(), $metadata));
                $updated++;
            } else {
                Log::error("Failed to transition order {$order->order_number} to status {$validated['status']}");
                $skipped++;
            }
        }

        Log::info("Bulk status update completed: {$updated} updated, {$skipped} skipped");

        return response()->json([
            'success' => $updated > 0,
            'message' => "Successfully updated {$updated} order(s)" . ($skipped > 0 ? " ({$skipped} skipped)" : ""),
            'updated_count' => $updated,
            'skipped_count' => $skipped
        ]);
    }

    /**
    /**
     * Get order details for an AJAX/JSON request.
     */
    public function getOrderDetails(Order $order)
    {
        // Load order with user and order items
        $order->load('user', 'orderItems.itemable');

        // Append custom accessors needed by the frontend modal
        $order->append([
            'status_badge',
            'formatted_total',
            'processing_days',
            'status_progress_class',
            'status_progress',
        ]);

        // Format items for the modal display
        $formattedItems = $order->orderItems->map(function(OrderItem $item) { // Added type hint
            // Use the fully qualified class name for ProductVariant check
            $itemableType = $item->itemable ? ($item->itemable instanceof ProductVariant ? 'variant' : 'product') : 'unknown';

            return [
                'name' => $item->item_name,
                'quantity' => $item->quantity,
                'price' => $item->unit_price,
                'total' => $item->total_price,
                'type' => $itemableType
            ];
        });

        return response()->json([
            'order' => $order,
            'formatted_items' => $formattedItems
        ]);
    }

    /**
     * Display simple invoice print view for order.
     */
    public function printInvoice(Order $order)
    {
        $order->load(['orderItems.itemable.attributeValues.attribute']);
        return view('order-invoice', compact('order'));
    }

    /**
     * Download invoice PDF for order.
     */
    public function downloadInvoice(Order $order)
    {
        $order->load(['orderItems.itemable.attributeValues.attribute']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('order-invoice-pdf', compact('order'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions([
                        'defaultFont' => 'DejaVu Sans',
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'chroot' => public_path(),
                    ]);

        $filename = 'invoice_' . ($order->order_number ?: 'WGC-ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT)) . '.pdf';

        return $pdf->download($filename);
    }
}
