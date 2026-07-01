<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NexusCertificationController;
use Illuminate\Support\Facades\Route;
Route::get('/careers', [HomeController::class, 'careers'])->name('careers');
Route::post('/careers', [\App\Http\Controllers\CareerController::class, 'store'])->name('careers.store');
Route::get('/new', [HomeController::class, 'newHome'])->name('new');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/coming-soon', function () {
    return view('pages.coming-soon');
})->name('coming-soon');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/categories', [ShopController::class, 'categories'])->name('categories');
Route::get('/brands', [ShopController::class, 'brands'])->name('brands');

Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Cart AJAX APIs
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');


// Payment & Shipping Integration Routes (porting from reference flow)
Route::post('/payment/razorpay/create-order', [\App\Http\Controllers\PaymentController::class, 'createOrder'])->name('razorpay.create_order');
Route::post('/payment/razorpay/verify', [\App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('razorpay.verify');
Route::post('/payment/razorpay/webhook', [\App\Http\Controllers\PaymentController::class, 'handleWebhook']);

Route::get('/shipping/couriers', [\App\Http\Controllers\ShiprocketController::class, 'checkCouriers'])->name('shipping.check_couriers');
Route::post('/shiprocket/webhook', [\App\Http\Controllers\ShiprocketController::class, 'handleWebhook']);

// Wishlist AJAX APIs
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // OTP Forgot Password Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendOTP'])->name('password.otp');
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOTP'])->name('password.verify.show');
    Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('password.verify.post');
    Route::get('/reset-password/{otp}/{email}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Auth Routes (Logged In Only)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/my-account', [AccountController::class, 'index'])->name('my-account');
    Route::post('/my-account/update', [AccountController::class, 'update'])->name('account.update');
    Route::post('/my-account/change-password', [AccountController::class, 'changePassword'])->name('account.password');
    
    // Address Management
    Route::post('/my-account/address', [AccountController::class, 'storeAddress'])->name('account.address.store');
    Route::post('/my-account/address/{id}', [AccountController::class, 'updateAddress'])->name('account.address.update');
    Route::delete('/my-account/address/{id}', [AccountController::class, 'deleteAddress'])->name('account.address.delete');
    
    // Product Review
    Route::post('/shop/{product:slug}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
});

Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog-details');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('pages.terms-and-conditions');
})->name('terms-and-conditions');

Route::get('/shipping-refund-policy', function () {
    return view('pages.shipping-refund-policy');
})->name('shipping-refund-policy');


Route::post('/contact', [ContactController::class, 'store'])->name('contact');
Route::post('/nexus-certification', [NexusCertificationController::class, 'store'])->name('nexus-certification.store');
