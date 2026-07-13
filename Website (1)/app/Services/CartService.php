<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Hydrate the session cart with full model data and calculate totals.
     * [PERFORMANCE] Uses batch loading to avoid N+1 queries.
     */
    public function getHydratedCart(array $cart): array
    {
        if (empty($cart)) return [];

        $productIds = array_unique(array_column($cart, 'product_id'));
        $variantIds = array_filter(array_unique(array_column($cart, 'variant_id')));

        $products = Product::whereIn('product_id', $productIds)->get()->keyBy('product_id');
        $variants = !empty($variantIds) ? ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id') : collect();

        $hydrated = [];
        foreach ($cart as $key => $item) {
            $product = $products->get($item['product_id']);
            if (!$product) continue;

            $variant = $item['variant_id'] ? $variants->get($item['variant_id']) : null;
            
            // Product-level discount check
            $unitPrice = $variant ? (float)$variant->discounted_price : (float)$product->mrp_price;
            $mrp = $variant ? (float)$variant->mrp_price : (float)$product->mrp_price;
            $productDiscount = $mrp - $unitPrice;

            $hydrated[$key] = [
                'item_key'       => $key,
                'product_id'     => $item['product_id'],
                'variant_id'     => $item['variant_id'] ? (int)$item['variant_id'] : null,
                'item_name'      => $product->name . ($variant ? " ({$variant->variant_label})" : ""),
                'name'           => $product->name,
                'price'          => $unitPrice,
                'unit_price'     => $unitPrice,
                'mrp'            => $mrp,
                'product_discount' => $productDiscount,
                'quantity'       => $item['quantity'],
                'row_total'      => $unitPrice * $item['quantity'],
                'image'          => $variant ? ($variant->variant_image_full_url ?? $product->image_full_url) : $product->image_full_url,
                'slug'           => $product->slug,
                'product'        => $product,
                'variant'        => $variant
            ];
        }

        // [SECURITY] Re-validate coupon if exists
        $this->revalidateSessionCoupon($hydrated);

        return $hydrated;
    }

    /**
     * Re-validate the coupon in session against current cart total.
     */
    protected function revalidateSessionCoupon(array &$hydratedCart)
    {
        if (!session()->has('coupon')) return;

        $subtotal = array_sum(array_column($hydratedCart, 'row_total'));
        $code = session('coupon.code');
        
        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            session()->forget('coupon');
            return;
        }

        // [LOGIC] Strict validation check
        list($valid, $error) = $coupon->isValid($subtotal, auth()->id(), session('user_email'));
        
        if (!$valid) {
            session()->forget('coupon');
            // We can optionally set a flash message here, but usually hydration happens on every page load.
            // controllers should handle user notification on explicit actions.
        } else {
            // Update the calculated discount amount in session
            session(['coupon.discount' => $coupon->calculateDiscount($subtotal)]);
        }
    }

    /**
     * Distribute total coupon discount across items proportionally.
     * [FINANCIAL] Handles the rounding remainder on the last item.
     */
    public function calculateProratedDiscounts(float $totalCouponDiscount, array $items): array
    {
        if ($totalCouponDiscount <= 0 || empty($items)) {
            foreach($items as &$item) $item['coupon_discount_share'] = 0;
            return $items;
        }

        $subtotal = array_sum(array_column($items, 'row_total'));
        if ($subtotal <= 0) return $items;

        $remainingDiscount = $totalCouponDiscount;
        $itemCount = count($items);
        $currentIndex = 0;

        foreach ($items as &$item) {
            $currentIndex++;
            if ($currentIndex === $itemCount) {
                // Last item gets the remainder to ensure exact match
                $item['coupon_discount_share'] = round($remainingDiscount, 2);
            } else {
                // Proportional share
                $share = ($item['row_total'] / $subtotal) * $totalCouponDiscount;
                $roundedShare = round($share, 2);
                $item['coupon_discount_share'] = $roundedShare;
                $remainingDiscount -= $roundedShare;
            }
        }

        return $items;
    }
}
