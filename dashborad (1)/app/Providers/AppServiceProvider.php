<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Order;
use App\Services\CacheService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register CacheService as singleton
        $this->app->singleton(CacheService::class, function () {
            return new CacheService();
        });
    }

    public function boot(): void
    {
        // Share sidebar counts globally
        view()->composer('layouts.sidebar', function ($view) {
            $view->with('pending_orders_count', \App\Models\Order::where('status', 'pending')->count());
            $view->with('processing_orders_count', \App\Models\Order::where('status', 'processing')->count());
            $view->with('low_stock_count', \App\Models\Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count());
        });

        // Setup custom route binding for Product to handle missing products gracefully
        $this->setupCustomRouteBindings();
        
        // Enhanced cache invalidation for all models
        $this->setupCacheInvalidation();
        
        // Setup cache warming in production or when explicitly requested
        if (App::environment('production') || config('product.cache.warm_on_start', false)) {
            $this->warmCaches();
        }
    }

    /**
     * Setup custom route bindings to handle missing models gracefully
     */
    private function setupCustomRouteBindings()
    {
        // Custom binding for Product to prevent 404 errors when product doesn't exist
        // This allows the controller to handle the error gracefully
        \Illuminate\Support\Facades\Route::bind('product', function ($value) {
            return Product::where('product_id', $value)->first() ?? new Product(['product_id' => $value]);
        });
    }

    /**
     * Setup comprehensive cache invalidation for all models
     */
    private function setupCacheInvalidation()
    {
        // Product cache invalidation
        Product::saved(function () {
            Cache::forget('product_filter_stats');
            Cache::forget('featured_products_8');
            $this->clearProductRelatedCaches();
        });

        Product::deleted(function () {
            Cache::forget('product_filter_stats');
            $this->clearProductRelatedCaches();
        });

        // Category cache invalidation
        Category::saved(function () {
            $this->clearCategoryRelatedCaches();
        });

        Category::deleted(function () {
            $this->clearCategoryRelatedCaches();
        });

        // Brand cache invalidation
        Brand::saved(function () {
            $this->clearBrandRelatedCaches();
        });

        Brand::deleted(function () {
            $this->clearBrandRelatedCaches();
        });

        // Banner cache invalidation
        Banner::saved(function () {
            $this->clearBannerRelatedCaches();
        });

        Banner::deleted(function () {
            $this->clearBannerRelatedCaches();
        });

        // Order cache invalidation
        Order::saved(function () {
            Cache::forget('order_stats_' . today()->format('Y-m-d'));
        });

        Order::deleted(function () {
            Cache::forget('order_stats_' . today()->format('Y-m-d'));
        });
    }

    /**
     * Warm up essential caches for better performance
     */
    private function warmCaches()
    {
        try {
            $cacheService = app(CacheService::class);
            $cacheService->preloadData();
            
            // Additional specific warmups
            Cache::put('app_warmed_at', now(), 3600);
            
            if (config('app.debug')) {
                info('AppServiceProvider: Cache warming completed');
            }
        } catch (\Exception $e) {
            // Log cache warming errors but don't fail application startup
            \Log::error('Cache warming failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear product-related caches
     */
    private function clearProductRelatedCaches()
    {
        $cacheKeys = [
            'product_total_count',
            'product_active_count',
            'product_stats',
            'product_filter_options',
            'featured_products_8',
            'product_filter_stats'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear category-specific product caches
        $categories = Category::pluck('category_id');
        foreach ($categories as $categoryId) {
            Cache::forget("products_category_{$categoryId}");
        }
    }

    /**
     * Clear category-related caches
     */
    private function clearCategoryRelatedCaches()
    {
        $cacheKeys = [
            'categories_total_count',
            'categories_active_count',
            'category_stats',
            'category_tree',
            'featured_categories'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear brand-related caches
     */
    private function clearBrandRelatedCaches()
    {
        $cacheKeys = [
            'brands_total_count',
            'brands_active_count',
            'brands_with_stats',
            'featured_brands',
            'brands_list'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear banner-related caches
     */
    private function clearBannerRelatedCaches()
    {
        $cacheKeys = [
            'banners_active',
            'banner_performance_data'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
