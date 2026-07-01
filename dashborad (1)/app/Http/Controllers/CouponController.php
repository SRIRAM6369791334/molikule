<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('usages')->latest()->paginate(15);
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:coupons,code',
            'discount_type'  => 'required|in:percentage,flat',
            'discount_value' => 'required|numeric|min:0',
            'min_cart_value' => 'nullable|numeric|min:0',
            'starts_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:starts_at',
            'usage_limit'    => 'nullable|integer|min:1',
            'user_limit'     => 'nullable|integer|min:1',
            'status'         => 'boolean',
        ]);

        Coupon::create($validated);

        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully!');
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_type'  => 'required|in:percentage,flat',
            'discount_value' => 'required|numeric|min:0',
            'min_cart_value' => 'nullable|numeric|min:0',
            'starts_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:starts_at',
            'usage_limit'    => 'nullable|integer|min:1',
            'user_limit'     => 'nullable|integer|min:1',
            'status'         => 'boolean',
        ]);

        $coupon->update($validated);

        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['status' => !$coupon->status]);
        return response()->json(['success' => true, 'status' => $coupon->status]);
    }
}
