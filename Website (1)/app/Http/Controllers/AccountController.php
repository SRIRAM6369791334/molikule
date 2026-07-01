<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display the authenticated user's account dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fetch order history for the user, ordered by newest first
        $orders = Order::with(['items.itemable' => function($morph) {
            $morph->morphWith([
                ProductVariant::class => ['product'],
            ]);
        }])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Fetch wishlist products from session
        $wishlistIds = session()->get('wishlist', []);
        $wishlistProducts = Product::whereIn('product_id', $wishlistIds)->with(['category', 'variants'])->get();

        // Fetch user addresses
        $addresses = \App\Models\UserAddress::where('user_id', $user->id)->get();

        // Dashboard Stats
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_wishlist' => count($wishlistIds),
            'total_addresses' => $addresses->count(),
        ];

        return view('pages.myaccount', compact('user', 'orders', 'wishlistProducts', 'addresses', 'stats'));
    }

    /**
     * Update the authenticated user's personal information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    /**
     * Store a new user address.
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'address_username' => 'required|string|max:255',
            'address_phone_number' => 'required|string|max:20',
            'address_line_one' => 'required|string|max:255',
            'address_line_two' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|numeric',
            'landmark' => 'nullable|string|max:255',
            'address_type_id' => 'required|integer',
        ]);

        $validated['user_id'] = Auth::id();
        
        \App\Models\UserAddress::create($validated);

        return redirect()->back()->with('success', 'Address added successfully.');
    }

    /**
     * Update an existing user address.
     */
    public function updateAddress(Request $request, $id)
    {
        $address = \App\Models\UserAddress::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'address_username' => 'required|string|max:255',
            'address_phone_number' => 'required|string|max:20',
            'address_line_one' => 'required|string|max:255',
            'address_line_two' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|numeric',
            'landmark' => 'nullable|string|max:255',
            'address_type_id' => 'required|integer',
        ]);

        $address->update($validated);

        return redirect()->back()->with('success', 'Address updated successfully.');
    }

    /**
     * Delete a user address.
     */
    public function deleteAddress($id)
    {
        $address = \App\Models\UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }
}

