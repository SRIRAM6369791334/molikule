<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = session('cart', []);
        $cartItems = $this->cartService->getHydratedCart($cart);
        $cartTotal = array_sum(array_column($cartItems, 'row_total'));

        return view('pages.cart', compact('cartItems', 'cartTotal'));
    }

    /**
     * Add an item to the cart (AJAX).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity'   => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $quantity = $request->quantity;

        $cart = session('cart', []);
        
        // Key the cart by both product and variant to allow multiple variants of same product
        $itemKey = $variantId ? "{$productId}_{$variantId}" : (string)$productId;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || $variant->stock_quantity < $quantity) {
                return response()->json(['success' => false, 'message' => 'Requested quantity is not available in stock.'], 422);
            }
        } else {
            $product = Product::find($productId);
            if (!$product || $product->stock_quantity < $quantity) {
                return response()->json(['success' => false, 'message' => 'Requested quantity is not available in stock.'], 422);
            }
        }

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += (int)$quantity;
        } else {
            $cart[$itemKey] = [
                'product_id' => (int)$productId,
                'variant_id' => $variantId ? (int)$variantId : null,
                'quantity'   => (int)$quantity,
            ];
        }

        session(['cart' => $cart]);
        
        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Product added to cart!',
                'cartCount' => cartCount(), // Uses a global helper if available
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Update item quantity (AJAX).
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        $itemKey = $request->item_key;

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] = (int)$request->quantity;
            session(['cart' => $cart]);

            $cartItems = $this->cartService->getHydratedCart($cart);
            $item = $cartItems[$itemKey] ?? null;
            $cartTotal = array_sum(array_column($cartItems, 'row_total'));
            $discount = session('coupon.discount', 0);

            return response()->json([
                'success'    => true,
                'message'    => 'Cart updated!',
                'cartCount'  => cartCount(),
                'item_total' => formatPrice($item['row_total'] ?? 0),
                'subtotal'   => formatPrice($cartTotal),
                'discount'   => formatPrice($discount),
                'grandTotal' => formatPrice($cartTotal - $discount),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }

    /**
     * Remove an item from the cart (AJAX).
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_key' => 'required|string',
        ]);

        $cart = session('cart', []);
        $itemKey = $request->item_key;

        if (isset($cart[$itemKey])) {
            unset($cart[$itemKey]);
            session(['cart' => $cart]);

            $cartItems = $this->cartService->getHydratedCart($cart);
            $cartTotal = array_sum(array_column($cartItems, 'row_total'));
            $discount = session('coupon.discount', 0);

            return response()->json([
                'success'    => true,
                'message'    => 'Item removed from cart!',
                'cartCount'  => cartCount(),
                'subtotal'   => formatPrice($cartTotal),
                'discount'   => formatPrice($discount),
                'grandTotal' => formatPrice($cartTotal - $discount),
                'isEmpty'    => count($cartItems) === 0
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }

    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $code = strtoupper($request->code);

        // [NOTE] No caching here — coupon must be fetched live so the usage
        // count is accurate for the UX pre-flight check. The real atomic
        // security gate (lockForUpdate) is in CheckoutController::process().
        $coupon = \App\Models\Coupon::with('usages')
            ->where('code', $code)
            ->active()
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        $cart = session('cart', []);
        $hydratedCart = $this->cartService->getHydratedCart($cart);
        $subtotal = array_sum(array_column($hydratedCart, 'row_total'));

        // Pre-flight check: gives immediate UX feedback (not the security gate)
        [$valid, $error] = $coupon->isValid($subtotal, auth()->id(), $request->email);

        if (!$valid) {
            return response()->json(['success' => false, 'message' => $error]);
        }

        $discountAmount = $coupon->calculateDiscount($subtotal);

        session(['coupon' => [
            'code'     => $coupon->code,
            'discount' => $discountAmount,
        ]]);

        return response()->json([
            'success'    => true,
            'message'    => 'Coupon applied!',
            'discount'   => '₹' . number_format($discountAmount, 2),
            'grandTotal' => '₹' . number_format($subtotal - $discountAmount, 2)
        ]);
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        
        $cart = session('cart', []);
        $hydratedCart = $this->cartService->getHydratedCart($cart);
        $subtotal = array_sum(array_column($hydratedCart, 'row_total'));

        return response()->json([
            'success'   => true, 
            'message'   => 'Coupon removed!',
            'subtotal'  => formatPrice($subtotal),
            'grandTotal' => formatPrice($subtotal)
        ]);
    }

    /**
     * Calculate total price of cart items.
     */
    private function calculateTotal(array $hydratedCart)
    {
        return array_sum(array_column($hydratedCart, 'row_total'));
    }
}

