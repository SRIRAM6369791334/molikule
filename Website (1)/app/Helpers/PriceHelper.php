<?php

/**
 * Molikule Global Helpers
 *
 * Registered in composer.json autoload files.
 * Available globally across controllers, views, and models.
 */

if (!function_exists('formatPrice')) {
    /**
     * Format a price value with the INR symbol.
     *
     * @param float|int|null $price
     */
    function formatPrice($price): string
    {
        if ($price === null) {
            return "\u{20B9}0.00";
        }

        return "\u{20B9}" . number_format((float) $price, 2);
    }
}

if (!function_exists('productImageUrl')) {
    /**
     * Build a full image URL from a relative path stored in the database.
     * Images are served from the Dashboard's public/uploads directory via MAIN_URL.
     */
    function productImageUrl(?string $relativePath): string
    {
        if (!$relativePath) {
            return asset('assets/images/shop/shop-15.png');
        }

        $relativePath = trim($relativePath);
        
        // If the path contains 'uploads/', extract everything AFTER 'uploads/' 
        // to ensure we always use the current MAIN_URL from .env
        if (str_contains($relativePath, 'uploads/')) {
            $parts = explode('uploads/', $relativePath);
            $relativePath = end($parts);
        } elseif (filter_var($relativePath, FILTER_VALIDATE_URL) !== false || str_starts_with($relativePath, 'http')) {
            // If it's a full URL but NOT an uploads URL (e.g. external link), return as is
            return $relativePath;
        }

        $baseUrl = rtrim(env('MAIN_URL', 'http://127.0.0.1:8001/'), '/');

        return $baseUrl . '/uploads/' . ltrim($relativePath, '/');
    }
}

if (!function_exists('cartCount')) {
    /**
     * Get the total number of items in the session cart.
     */
    function cartCount(): int
    {
        $cart = session('cart', []);

        return array_sum(array_column($cart, 'quantity'));
    }
}

if (!function_exists('wishlistCount')) {
    /**
     * Get the total number of items in the session wishlist.
     */
    function wishlistCount(): int
    {
        return count(session('wishlist', []));
    }
}

if (!function_exists('isInWishlist')) {
    /**
     * Check if a product is in the wishlist.
     *
     * @param int|string $productId
     */
    function isInWishlist($productId): bool
    {
        $wishlist = session('wishlist', []);

        return in_array($productId, $wishlist);
    }
}
