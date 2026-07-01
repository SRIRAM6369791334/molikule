<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PincodeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\BentoCardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductRecordController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\NexusCertificationController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Email Test Route
    Route::get('/test-email', [TestController::class, 'testEmail'])->name('test-email');

    // User Management
    Route::get('/users', [AuthController::class, 'users'])->name('users');
    Route::get('/customers', [AuthController::class, 'customers'])->name('customers');
    Route::get('/users/{user}', [AuthController::class, 'showUser'])->name('users.show');
    Route::get('/users/create', [AuthController::class, 'showCreateUserForm'])->name('users.create');
    Route::post('/users', [AuthController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AuthController::class, 'showEditUserForm'])->name('users.edit');
    Route::put('/users/{user}', [AuthController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AuthController::class, 'deleteUser'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [AuthController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // Product Management
    Route::get('products/download-template', [ProductController::class, 'downloadTemplate'])->name('products.download-template');
    Route::post('products/bulk-upload', [ProductController::class, 'bulkUpload'])->name('products.bulk-upload');
    Route::post('products/{id}/upload-image-ajax', [ProductController::class, 'uploadImageAjax'])->name('products.upload-image-ajax');
    Route::resource('products', ProductController::class);
    Route::get('/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::get('/stocks', [ProductController::class, 'stocks'])->name('products.stocks');
    Route::post('/stocks/update', [ProductController::class, 'updateStock'])->name('products.update-stock');
    Route::get('/stocks-ajax', [ProductController::class, 'stocksAjax'])->name('products.stocks-ajax');
    Route::get('/products-ajax', [ProductController::class, 'ajaxIndex'])->name('products.ajax');
    Route::get('/add-product', [ProductController::class, 'create'])->name('products.create.form');
    Route::get('/products-filter-stats', [ProductController::class, 'filterStats'])->name('products.filter-stats');
    Route::post('/products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulk-update');
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

    Route::get('categories/download-template', [CategoryController::class, 'downloadTemplate'])->name('categories.download-template');
    Route::post('categories/bulk-upload', [CategoryController::class, 'bulkUpload'])->name('categories.bulk-upload');
    Route::resource('categories', CategoryController::class);
    Route::post('categories/bulk-update', [CategoryController::class, 'bulkUpdate'])->name('categories.bulk-update');
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::get('categories-stats', [CategoryController::class, 'getStats'])->name('categories.stats');
    Route::get('categories-ajax', [CategoryController::class, 'getCategoriesAjax'])->name('categories.ajax');
    Route::get('categories-grid-ajax', [CategoryController::class, 'ajaxIndex'])->name('categories.grid-ajax');

    Route::get('brands/download-template', [BrandController::class, 'downloadTemplate'])->name('brands.download-template');
    Route::post('brands/bulk-upload', [BrandController::class, 'bulkUpload'])->name('brands.bulk-upload');
    Route::resource('brands', BrandController::class);
    Route::post('brands/bulk-update', [BrandController::class, 'bulkUpdate'])->name('brands.bulk-update');
    Route::post('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    Route::get('brands-stats', [BrandController::class, 'getStats'])->name('brands.stats');
    Route::get('brands-ajax', [BrandController::class, 'getBrandsAjax'])->name('brands.ajax');

    // Category AJAX (Kumarimall Compatible)
    Route::post('updateCategories/{id}', [CategoryController::class, 'update']);
    Route::post('destroyCategories/{id}', [CategoryController::class, 'destroy']);
    
    // Brand AJAX (Kumarimall Compatible)
    Route::post('updateBrands/{id}', [BrandController::class, 'update']);
    Route::post('destroyBrands/{id}', [BrandController::class, 'destroy']);

    // Pincodes
    Route::resource('pincodes', PincodeController::class);
    Route::post('pincodes/bulk-update', [PincodeController::class, 'bulkUpdate'])->name('pincodes.bulk-update');
    Route::post('pincodes/{pincode}/toggle-status', [PincodeController::class, 'toggleStatus'])->name('pincodes.toggle-status');
    Route::get('pincodes-stats', [App\Http\Controllers\PincodeController::class, 'getStats'])->name('pincodes.stats');
    Route::get('pincodes-ajax', [App\Http\Controllers\PincodeController::class, 'getPincodesAjax'])->name('pincodes.ajax');
    Route::post('pincodes/validate-delivery', [App\Http\Controllers\PincodeController::class, 'validateForDelivery'])->name('pincodes.validate-delivery');

    // Product Variants
    // Product Variants & Advanced Logic
    Route::get('product-variants/get-categories', [ProductVariantController::class, 'getCategoriesByBrandAjax'])->name('product-variants.get-categories');
    Route::get('product-variants/get-products/{category?}', [ProductVariantController::class, 'getProductsAjax'])->name('product-variants.get-products');
    Route::post('product-variants/store', [ProductVariantController::class, 'ajaxStore'])->name('product-variants.ajax-store');
    Route::post('product-variants/update/{product_variant}', [ProductVariantController::class, 'ajaxUpdate'])->name('product-variants.ajax-update');
    Route::get('product-variants/edit/{product_variant}', [ProductVariantController::class, 'ajaxEdit'])->name('product-variants.ajax-edit');
    Route::post('product-variants/destroy/{product_variant}', [ProductVariantController::class, 'ajaxDestroy'])->name('product-variants.ajax-destroy');

    Route::get('product-variants/download-template', [ProductVariantController::class, 'downloadTemplate'])->name('product-variants.download-template');
    Route::post('product-variants/bulk-upload', [ProductVariantController::class, 'bulkUpload'])->name('product-variants.bulk-upload');
    Route::get('product-variants/bulk-create', [ProductVariantController::class, 'bulkCreate'])->name('product-variants.bulk-create');
    Route::post('product-variants/bulk-store', [ProductVariantController::class, 'bulkStore'])->name('product-variants.bulk-store');
    Route::post('product-variants/{id}/upload-image-ajax', [ProductVariantController::class, 'uploadImageAjax'])->name('product-variants.upload-image-ajax');
    Route::resource('product-variants', ProductVariantController::class)->parameters(['product-variant' => 'product_variant']);
    Route::post('product-variants/bulk-update', [ProductVariantController::class, 'bulkUpdate'])->name('product-variants.bulk-update');
    Route::post('product-variants/{product_variant}/toggle-status', [ProductVariantController::class, 'toggleStatus'])->name('product-variants.toggle-status');

    // Product-specific variants
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/', [ProductVariantController::class, 'productVariants'])->name('index');
        Route::get('/create', [ProductVariantController::class, 'createForProduct'])->name('create');
        Route::post('/', [ProductVariantController::class, 'storeForProduct'])->name('store');
        Route::get('/{product_variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{product_variant}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{product_variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
    });

    Route::resource('banners', BannerController::class);
    Route::post('banners/bulk-update', [BannerController::class, 'bulkUpdate'])->name('banners.bulk-update');
    Route::post('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    Route::get('banners-stats', [BannerController::class, 'getStats'])->name('banners.stats');
    Route::get('banners-ajax', [BannerController::class, 'getBannersAjax'])->name('banners.ajax');

    // Certificates
    Route::resource('certificates', CertificateController::class)->except(['show']);
    Route::post('certificates/{certificate}/toggle-status', [CertificateController::class, 'toggleStatus'])->name('certificates.toggle-status');

    // Bento Cards
    Route::resource('bento-cards', BentoCardController::class)->except(['show']);

    // Search Management
    Route::get('/search', [SearchController::class, 'showResults'])->name('search.results');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    Route::get('/api/search', [SearchController::class, 'search'])->name('search.api');

    // Contact Messages
    Route::get('contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts.index');
    Route::post('contacts/{contact}/mark-read', [App\Http\Controllers\ContactController::class, 'markAsRead'])->name('contacts.mark-read');
    Route::post('contacts/{contact}/mark-unread', [App\Http\Controllers\ContactController::class, 'markAsUnread'])->name('contacts.mark-unread');
    Route::delete('contacts/{contact}', [App\Http\Controllers\ContactController::class, 'destroy'])->name('contacts.destroy');

    // NEXUS Certification Enquiries
    Route::get('nexus-certifications', [NexusCertificationController::class, 'index'])->name('nexus-certifications.index');
    Route::post('nexus-certifications/{enquiry}/mark-read', [NexusCertificationController::class, 'markAsRead'])->name('nexus-certifications.mark-read');
    Route::post('nexus-certifications/{enquiry}/mark-unread', [NexusCertificationController::class, 'markAsUnread'])->name('nexus-certifications.mark-unread');
    Route::delete('nexus-certifications/{enquiry}', [NexusCertificationController::class, 'destroy'])->name('nexus-certifications.destroy');

    // Job Applications
    Route::get('job-applications', [App\Http\Controllers\JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('job-applications/{jobApplication}', [App\Http\Controllers\JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::post('job-applications/{jobApplication}/mark-read', [App\Http\Controllers\JobApplicationController::class, 'markAsRead'])->name('job-applications.mark-read');
    Route::post('job-applications/{jobApplication}/mark-unread', [App\Http\Controllers\JobApplicationController::class, 'markAsUnread'])->name('job-applications.mark-unread');
    Route::delete('job-applications/{jobApplication}', [App\Http\Controllers\JobApplicationController::class, 'destroy'])->name('job-applications.destroy');

    // Job Positions
    Route::resource('job-positions', App\Http\Controllers\JobPositionController::class)->except(['create', 'show', 'edit']);
    Route::post('job-positions/{jobPosition}/toggle-status', [App\Http\Controllers\JobPositionController::class, 'toggleStatus'])->name('job-positions.toggle-status');

    // Order Management
    Route::resource('orders', OrderController::class);
    Route::post('orders/bulk-status-update', [OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-status-update');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/details', [OrderController::class, 'getOrderDetails'])->name('orders.details');

    // Coupon Management
    Route::resource('coupons', \App\Http\Controllers\CouponController::class);
    Route::post('coupons/{coupon}/toggle', [\App\Http\Controllers\CouponController::class, 'toggleStatus'])->name('coupons.toggle');

    // Blog Management
    Route::resource('blogs', BlogController::class)->except(['show']);
    Route::post('blogs/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');

    // Reviews Management
    Route::get('/reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/toggle-status', [ProductReviewController::class, 'toggleStatus'])->name('reviews.toggle-status');
    Route::delete('/reviews/{review}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');


    // Order Status-specific Routes
    Route::get('/all-orders', [OrderController::class, 'index'])->name('all-orders.index');
    Route::get('/pending-orders', [OrderController::class, 'pending'])->name('pending-orders');
    Route::get('/processing-orders', [OrderController::class, 'processing'])->name('processing-orders');
    Route::get('/dispatch-orders', [OrderController::class, 'dispatched'])->name('dispatch-orders.index');
    Route::get('/delivered-orders', [OrderController::class, 'delivered'])->name('delivered-orders.index');
    Route::get('/today-orders', [OrderController::class, 'todayOrders'])->name('today-orders.index');

    // Simple Order Invoice (like insurance print functionality)
    Route::get('/orders/{order}/invoice', [OrderController::class, 'printInvoice'])->name('orders.invoice.print');
    Route::get('/orders/{order}/invoice/view', [OrderController::class, 'printInvoice'])->name('orders.invoice.view');
    Route::get('/orders/{order}/invoice/download', [OrderController::class, 'downloadInvoice'])->name('orders.invoice.download');
    Route::get('/orders/{order}/invoice/generate', [OrderController::class, 'downloadInvoice'])->name('orders.invoice.generate');

    // Insurance Management (Read-only)
    Route::get('/insurance', [InsuranceController::class, 'index'])->name('insurance.index');
    Route::get('/insurance/{insurance}', [InsuranceController::class, 'show'])->name('insurance.show');
    Route::get('/insurance/{insurance}/print', [InsuranceController::class, 'print'])->name('insurance.print');

    // Product Creation Records (Audit Logs)
    Route::get('/product-records', [ProductRecordController::class, 'index'])->name('product-records.index');
    Route::get('/product-records/{id}', [ProductRecordController::class, 'show'])->name('product-records.show');
});

