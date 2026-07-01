<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $productId = $request->product_id;
        $wishlist = session()->get('wishlist', []);

        if (in_array($productId, $wishlist)) {
            $wishlist = array_diff($wishlist, [$productId]);
            $status = 'removed';
        } else {
            $wishlist[] = $productId;
            $status = 'added';
        }

        session()->put('wishlist', array_values($wishlist));

        return response()->json([
            'success' => true,
            'status' => $status,
            'count' => count($wishlist)
        ]);
    }

    public function index()
    {
        $wishlist = session()->get('wishlist', []);
        $products = Product::whereIn('product_id', $wishlist)->get();

        return view('pages.wishlist', [
            'products' => $products,
            'title' => 'My Wishlist'
        ]);
    }
}
