<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'Please login to write a review.');
        }

        $user = Auth::user();

        // Check eligibility: Delivered order containing a variant of this product
        $hasBought = Order::where('user_id', $user->id)
            ->where('status', Order::STATUS_DELIVERY)
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where(function ($q) use ($product) {
                    // Check if it's a direct product link
                    $q->where('itemable_type', 'App\Models\Product')
                      ->where('itemable_id', $product->product_id);
                })->orWhere(function ($q) use ($product) {
                    // Check if it's a variant link
                    $q->where('itemable_type', 'App\Models\ProductVariant')
                      ->whereIn('itemable_id', function ($sub) use ($product) {
                          $sub->select('id')
                              ->from('product_variants')
                              ->where('product_id', $product->product_id);
                      });
                });
            })
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'You can only review products you have purchased and received.');
        }

        // Check if already reviewed
        $alreadyReviewed = ProductReview::where('user_id', $user->id)
            ->where('product_id', $product->product_id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Find the variant they bought (optional, just for context)
        $variant = OrderItem::whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', Order::STATUS_DELIVERY);
            })
            ->where('itemable_type', 'App\Models\ProductVariant')
            ->whereIn('itemable_id', function ($sub) use ($product) {
                $sub->select('id')->from('product_variants')->where('product_id', $product->product_id);
            })
            ->first();

        ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $product->product_id,
            'variant_id' => $variant ? $variant->itemable_id : null,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Review Submitted! Your review has been submitted successfully and is awaiting administrator approval.');
    }
}
