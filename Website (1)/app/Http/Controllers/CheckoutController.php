<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop')->with('info', 'Your cart is empty.');
        }

        $cartItems = $this->cartService->getHydratedCart($cart);
        $cartTotal = array_sum(array_column($cartItems, 'row_total'));
        
        // [LOGIC] Apply coupon if exists in session
        $couponAmount = 0;
        if (session()->has('coupon')) {
            $couponAmount = session('coupon.discount');
        }

        // Provide the pickup pincode for frontend serviceability checks
        $pickupPincode = config('services.shiprocket.pickup_pincode');

        return view('pages.checkout', compact('cartItems', 'cartTotal', 'pickupPincode', 'couponAmount'));

    }

    /**
     * Process the checkout form submission.
     */
    public function process(Request $request)
    {
        $request->validate([
            'fname'           => 'required|string|max:255',
            'lname'           => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:255', // Line 1
            'address_line_2'  => 'nullable|string|max:255', // Line 2
            'landmark'        => 'nullable|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'zip'             => 'required|string|max:20',
            'payment_method'  => 'required|in:cash,razorpay',
            'shipping_rate'   => 'nullable|numeric',
            'courier_id'      => 'nullable|string',
            'order_notes'     => 'nullable|string|max:1000',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        $hydratedCart = $this->cartService->getHydratedCart($cart);
        $cartTotal = array_sum(array_column($hydratedCart, 'row_total'));
        
        $shippingCost = (float) $request->input('shipping_rate', 0);
        $userId = auth()->id();
        if (!$userId && $request->filled('email')) {
            $existingUser = \App\Models\User::where('email', $request->email)->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            }
        }

        try {
            DB::beginTransaction();

            // ================================================================
            // [ATOMIC CONCURRENCY LOCK] — Coupon Validation Gate
            //
            // PROBLEM: 100 users hit checkout simultaneously. All read
            //          usage_count = 4 before any write completes → all pass
            //          → 100 redemptions for a 5-slot coupon. CATASTROPHIC.
            //
            // SOLUTION: lockForUpdate() tells MySQL/PostgreSQL to place an
            //           exclusive row-level lock on this coupon record.
            //           Every other transaction that tries to touch this row
            //           is BLOCKED until this transaction commits or rolls back.
            //           Only one request at a time can pass this gate.
            //
            // withCount('usages') fetches the usage count atomically within
            //           the same locked read, so we never rely on a stale
            //           pre-lock count.
            //
            // CouponUsage::create() is placed INSIDE the transaction (before
            //           DB::commit()) so the +1 write and the count check are
            //           one atomic unit. No double-redemption possible.
            // ================================================================
            $couponDiscount = 0;
            $couponCode     = null;
            $coupon         = null; // keep reference for usage recording below

            if (session()->has('coupon')) {
                $couponCode = session('coupon.code');

                // --- STEP 1: Acquire exclusive row lock + read live usage count ---
                $coupon = Coupon::withCount('usages')
                    ->where('code', $couponCode)
                    ->lockForUpdate()   // <-- Exclusive lock. All other concurrent
                    ->first();          //     transactions WAIT here until we commit.

                if (!$coupon) {
                    // Coupon deleted between "apply" and "checkout"
                    DB::rollBack();
                    session()->forget('coupon');
                    return response()->json(['success' => false, 'message' => 'Coupon no longer exists.'], 422);
                }

                // --- STEP 2: Validate using the POST-LOCK usage count ---
                // $coupon->usages_count is the count read AFTER the lock was
                // acquired — guaranteed accurate for this transaction window.
                [$valid, $error] = $coupon->isValid(
                    $cartTotal,
                    $userId,
                    $request->email,
                    $coupon->usages_count  // <-- Locked, accurate count passed in
                );

                if (!$valid) {
                    DB::rollBack();
                    session()->forget('coupon');
                    \Log::warning("Coupon [{$couponCode}] rejected at checkout gate.", [
                        'user_id'      => $userId,
                        'usages_count' => $coupon->usages_count,
                        'usage_limit'  => $coupon->usage_limit,
                        'reason'       => $error,
                    ]);
                    return response()->json(['success' => false, 'message' => 'Coupon invalidated: ' . $error], 422);
                }

                $couponDiscount = $coupon->calculateDiscount($cartTotal);
            }

            $grandTotal = ($cartTotal - $couponDiscount) + $shippingCost;

            // [FINANCIAL] Calculate Prorated Coupon Discounts for snapshots
            $hydratedCart = $this->cartService->calculateProratedDiscounts($couponDiscount, $hydratedCart);

            $orderNumber = Order::generateOrderNumber();
            // $userId is already resolved above and mapped to existing user if email matches

            // Assemble full address string
            $fullAddressParts = [
                $request->address,
                $request->address_line_2,
                $request->landmark,
                $request->city,
                $request->state,
                $request->zip
            ];
            $fullAddressString = implode(', ', array_filter($fullAddressParts));

            // 1. Save Address to UserAddress table
            $billingAddress = \App\Models\UserAddress::create([
                'address_username'      => $request->fname . ' ' . $request->lname,
                'address_first_name'    => $request->fname,
                'address_last_name'     => $request->lname,
                'user_id'               => $userId,
                'address_line_one'      => $request->address,
                'address_line_two'      => $request->address_line_2,
                'landmark'              => $request->landmark,
                'city'                  => $request->city,
                'state'                 => $request->state,
                'pincode'               => $request->zip,
                'address_phone_number'  => $request->phone,
                'address_type_id'       => 1, // Billing
                'address_type_name'     => 'Billing',
            ]);

            // 2. Create Order
            $order = Order::create([
                'order_number'    => $orderNumber,
                'user_id'         => $userId,
                'customer_name'   => $request->fname . ' ' . $request->lname,
                'customer_email'  => $request->email,
                'customer_phone'  => $request->phone,
                'shipping_address' => $fullAddressString,
                'billing_address'  => $fullAddressString,
                'pincode'         => $request->zip,
                'shipping_cost'   => $shippingCost,
                'discount_amount' => array_sum(array_column($hydratedCart, 'discount') ?? []),
                'coupon_code'     => $couponCode,
                'coupon_discount' => $couponDiscount,
                'total_amount'    => $grandTotal,
                'status'          => Order::STATUS_PENDING,
                'payment_status'  => 'pending',
                'payment_method'  => $request->payment_method === 'razorpay' ? 'online' : 'cash',
                'notes'           => $request->order_notes,
                'courier_name'    => $request->courier_name ?? null,
                'courier_id'      => $request->courier_id ?: null,
                'version'         => 1
            ]);

            // --- STEP 3: Write CouponUsage INSIDE the transaction (before commit) ---
            // This is critical: the +1 write must happen BEFORE DB::commit()
            // releases the row lock. Any concurrent transaction blocked at
            // lockForUpdate() will now see the updated count when it proceeds.
            if ($coupon && $couponDiscount > 0) {
                \App\Models\CouponUsage::create([
                    'coupon_id'      => $coupon->id,
                    'user_id'        => $userId,
                    'order_id'       => $order->id,
                    'customer_email' => $request->email,
                    'created_at'     => now(),
                ]);
            }


            // 3. Save to order_items, product_slots, and product_order_user_addresses
            \App\Models\ProductOrderUserAddress::create([
                'order_id'              => $orderNumber,
                'user_id'               => $userId,
                'address_line_one'      => $request->address,
                'address_line_two'      => $request->address_line_2,
                'landmark'              => $request->landmark,
                'city'                  => $request->city,
                'state'                 => $request->state,
                'pincode'               => $request->zip,
                'address_phone_number' => $request->phone,
                'address_type_id' => 1,
                'address_type_name' => 'Billing',
            ]);

            foreach ($hydratedCart as $item) {
                // Item creation for legacy compatibility
                $itemableType = $item['variant_id'] ? ProductVariant::class : Product::class;
                $itemableId = $item['variant_id'] ?: $item['product_id'];

                OrderItem::create([
                    'order_id'      => $order->id,
                    'itemable_type' => $itemableType,
                    'itemable_id'   => $itemableId,
                    'item_name'     => $item['name'],
                    'unit_price'    => $item['unit_price'],
                    'quantity'      => $item['quantity'],
                    'total_price'   => $item['row_total'],
                    'item_options'  => json_encode(['image' => $item['image'], 'slug' => $item['slug']])
                ]);

                // --- STOCK DEDUCTION LOGIC (WITH ATOMIC CONCURRENCY LOCK) ---
                if ($itemableType === ProductVariant::class) {
                    $variant = ProductVariant::where('id', $itemableId)->lockForUpdate()->first();
                    if ($variant) {
                        // Check if stock is sufficient (post-lock read)
                        if ($variant->stock_quantity < $item['quantity']) {
                            throw new \Exception("Insufficient stock for {$item['name']}. Available: {$variant->stock_quantity}");
                        }
                        
                        $variant->decrement('stock_quantity', $item['quantity']);
                        
                        // Sync master product stock
                        if ($variant->product) {
                            $variant->product->increment('stock_quantity', -$item['quantity']);
                        }
                    }
                } else {
                    $product = Product::where('product_id', $itemableId)->lockForUpdate()->first();
                    if ($product) {
                        // Check if stock is sufficient (post-lock read)
                        if ($product->stock_quantity < $item['quantity']) {
                            throw new \Exception("Insufficient stock for {$item['name']}. Available: {$product->stock_quantity}");
                        }
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                // --- AUDIT LOGGING ---
                \App\Models\InventoryTransaction::create([
                    'product_id'   => $item['product_id'],
                    'variant_id'   => $item['variant_id'] ?: null,
                    'type'         => 'order',
                    'quantity'     => -$item['quantity'],
                    'reference_id' => $order->id,
                    'note'         => 'Website Order #' . $orderNumber,
                    'created_by'   => $userId
                ]);

                // ProductSlot snapshot (with proration)
                \App\Models\ProductSlot::create([
                    'order_id' => $orderNumber,
                    'product_id' => $item['product_id'],
                    'product_varient_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'product_image' => $item['image'],
                    'product_rate' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'product_total' => $item['row_total'],
                    'discount' => ($item['product_discount'] * $item['quantity']) + ($item['coupon_discount_share'] ?? 0),
                    'delivery_status' => 0,
                    'approve_staus' => 1,
                ]);

                // OrderFullDetail snapshot (auditable proration)
                \App\Models\OrderFullDetail::create([
                    'order_id'              => $order->id,
                    'order_number'          => $orderNumber,
                    'user_id'               => $userId,
                    'user_email'            => $request->email,
                    'user_name'             => $request->fname . ' ' . $request->lname,
                    'user_phone'            => $request->phone,
                    'product_id'            => $item['product_id'],
                    'product_name'          => $item['name'],
                    'product_image'         => $item['image'],
                    'product_slug'          => $item['slug'],
                    'variant_id'            => $item['variant_id'],
                    'variant_mrp_price'     => $item['mrp'],
                    'order_quantity'        => $item['quantity'],
                    'order_unit_price'      => $item['unit_price'],
                    'order_total_price'     => $item['row_total'] - ($item['coupon_discount_share'] ?? 0),
                    'order_delivery_charge' => $shippingCost,
                    'order_discount_amount' => $item['product_discount'] * $item['quantity'] + ($item['coupon_discount_share'] ?? 0),
                    'order_grand_total'     => $grandTotal,
                    'payment_method'        => $request->payment_method === 'razorpay' ? 'online' : 'cash',
                    'payment_status'        => 'pending',
                    'shipping_address'      => $fullAddressString,
                    'billing_address'       => $fullAddressString,
                    'order_created_at'      => now(),
                ]);

            }

            DB::commit();

            // For COD, redirect to success. For Razorpay, return order info to trigger JS.
            if ($request->payment_method === 'cash') {
                // Auto-sync COD to Shiprocket
                try {
                    $shiprocket = new \App\Services\ShiprocketService();
                    $shiprocket->createOrder($order, $request->courier_id ? (int)$request->courier_id : null);
                    \App\Models\ProductTracking::syncFromOrder($order->fresh());
                } catch (\Exception $se) {
                    \Log::error('Checkout: Shiprocket sync failed for COD', ['error' => $se->getMessage()]);
                }

                session(['order_number' => $orderNumber]);
                session()->forget(['cart', 'coupon']);

                // Send Notifications
                $this->sendOrderNotifications($order->fresh());

                return response()->json(['success' => true, 'redirect' => route('checkout.success'), 'order_number' => $orderNumber]);
            } else {
                // Return data for Razorpay modal
                return response()->json([
                    'success' => true,
                    'payment_method' => 'razorpay',
                    'order_number' => $orderNumber,
                    'amount' => $grandTotal,
                    'customer' => [
                        'name' => $request->fname . ' ' . $request->lname,
                        'email' => $request->email,
                        'phone' => $request->phone
                    ]
                ]);
            }


        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Process Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the success page.
     */
    public function success()
    {
        $orderNumber = session('order_number') ?? request('order_number');
        if (!$orderNumber) {
            return redirect()->route('home');
        }

        session(['order_number' => $orderNumber]);

        return view('pages.thankyou', compact('orderNumber'));
    }

    /**
     * Send order notifications to customer and admin.
     */
    public function sendOrderNotifications(Order $order)
    {
        try {
            // 1. Send to Customer
            Mail::send('emails.order-confirmation', ['order' => $order], function($message) use ($order) {
                $message->to($order->customer_email);
                $message->subject('Order Confirmation - #' . $order->order_number . ' | Molikule');
            });

            // 2. Send to Admin
            $adminEmail = env('ADMIN_EMAIL');
            if ($adminEmail) {
                Mail::send('emails.admin-order-notification', ['order' => $order], function($message) use ($order, $adminEmail) {
                    $message->to($adminEmail);
                    $message->subject('NEW ORDER: #' . $order->order_number . ' (' . formatPrice($order->total_amount) . ')');
                });
            }
        } catch (\Exception $e) {
            Log::error("Order notification failed for #{$order->order_number}: " . $e->getMessage());
        }
    }
}


