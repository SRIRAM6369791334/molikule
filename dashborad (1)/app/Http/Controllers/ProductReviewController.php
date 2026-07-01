<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with(['user', 'product', 'variant']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uQ) use ($search) {
                      $uQ->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($pQ) use ($search) {
                      $pQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(15);
        return view('reviews.index', compact('reviews'));
    }

    public function toggleStatus(ProductReview $review)
    {
        $review->is_approved = !$review->is_approved;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review status updated successfully',
            'is_approved' => $review->is_approved
        ]);
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }
}
