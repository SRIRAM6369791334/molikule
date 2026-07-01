<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;

class CacheService
{
    const PRODUCT_CACHE_TTL = 3600; // 1 hour
    const CATEGORY_CACHE_TTL = 300; // 5 minutes
    const BRAND_CACHE_TTL = 1800; // 30 minutes
    const BANNER_CACHE_TTL = 300; // 5 minutes
    const ANALYTICS_CACHE_TTL = 1800; // 30 minutes

    /**
     * Cache product data with tag-based invalidation
     */
    public function cacheProduct($productId, $callback, $tags = [])
    {
        $key = "product_{$productId}";
        $tags = array_merge(['products'], $tags);
        
        if (method_exists(Cache::getStore(), 'tags')) {
            return Cache::tags($tags)->remember($key, self::PRODUCT_CACHE_TTL, $callback);
        } else {
            // Fallback for stores that don't support tags
            return Cache::remember($key, self::PRODUCT_CACHE_TTL, $callback);
        }
    }

    /**
     * Cache category data with tag-based invalidation
     */
    public function cacheCategory($categoryId, $callback, $tags = [])
    {
        $key = "category_{$categoryId}";
        $tags = array_merge(['categories'], $tags);
        
        if (method_exists(Cache::getStore(), 'tags')) {
            return Cache::tags($tags)->remember($key, self::CATEGORY_CACHE_TTL, $callback);
        } else {
            return Cache::remember($key, self::CATEGORY_CACHE_TTL, $callback);
        }
    }

    /**
     * Cache brand data with tag-based invalidation
     */
    public function cacheBrand($brandId, $callback, $tags = [])
    {
        $key = "brand_{$brandId}";
        $tags = array_merge(['brands'], $tags);
        
        if (method_exists(Cache::getStore(), 'tags')) {
            return Cache::tags($tags)->remember($key, self::BRAND_CACHE_TTL, $callback);
        } else {
            return Cache::remember($key, self::BRAND_CACHE_TTL, $callback);
        }
    }

    /**
     * Cache banner data with tag-based invalidation
     */
    public function cacheBanner($bannerId, $callback, $tags = [])
    {
        $key = "banner_{$bannerId}";
        $tags = array_merge(['banners'], $tags);
        
        if (method_exists(Cache::getStore(), 'tags')) {
            return Cache::tags($tags)->remember($key, self::BANNER_CACHE_TTL, $callback);
        } else {
            return Cache::remember($key, self::BANNER_CACHE_TTL, $callback);
        }
    }

    /**
     * Cache collections with pagination support
     */
    public function cacheCollection($key, $callback, $ttl = 1800, $tags = [])
    {
        if (method_exists(Cache::getStore(), 'tags')) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        } else {
            return Cache::remember($key, $ttl, $callback);
        }
    }

    /**
     * Cache search results
     */
    public function cacheSearchResults($query, $filters, $callback, $ttl = 300)
    {
        $cacheKey = 'search_' . md5($query . serialize($filters));
        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Invalidate multiple cache keys with pattern matching
     */
    public function invalidateCache($pattern, $tags = [])
    {
        if (method_exists(Cache::getStore(), 'tags') && !empty($tags)) {
            Cache::tags($tags)->flush();
        } else {
            // Fallback for stores that don't support tags
            $this->clearCacheByPattern($pattern);
        }
    }

    /**
     * Clear specific cache by key
     */
    public function clearCache($key)
    {
        Cache::forget($key);
    }

    /**
     * Clear all cache related to a specific entity
     */
    public function clearEntityCache($entityType, $entityId, $relatedIds = [])
    {
        $baseKey = "{$entityType}_{$entityId}";
        
        // Clear main entity cache
        Cache::forget($baseKey);
        
        // Clear related caches
        foreach ($relatedIds as $relatedId) {
            Cache::forget("{$entityType}_{$relatedId}");
        }
        
        // Clear collections
        $collectionPatterns = [
            "{$entityType}s_list_*",
            "{$entityType}s_*_page_*",
            "search_{$entityType}*"
        ];
        
        foreach ($collectionPatterns as $pattern) {
            $this->clearCacheByPattern($pattern);
        }
    }

    /**
     * Clear all product-related caches
     */
    public function clearProductCaches($productId = null, $categoryId = null, $brandId = null)
    {
        $tags = ['products'];
        
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tags)->flush();
        } else {
            // Clear specific patterns
            if ($productId) {
                Cache::forget("product_{$productId}");
            }
            
            $patterns = ['products_list_*', 'products_*_page_*', 'featured_products_*', 'search_product*'];
            foreach ($patterns as $pattern) {
                $this->clearCacheByPattern($pattern);
            }
        }
    }

    /**
     * Clear all category-related caches
     */
    public function clearCategoryCaches($categoryId = null)
    {
        $tags = ['categories'];
        
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tags)->flush();
        } else {
            if ($categoryId) {
                Cache::forget("category_{$categoryId}");
            }
            
            $patterns = ['categories_list_*', 'categories_tree_*', 'categories_*_page_*'];
            foreach ($patterns as $pattern) {
                $this->clearCacheByPattern($pattern);
            }
        }
        
        // Clear CategoryService specific cache keys
        $this->clearCategoryServiceCaches();
    }
    
    /**
     * Clear CategoryService specific cache keys
     */
    private function clearCategoryServiceCaches()
    {
        // Clear stats caches
        $statsKeys = [
            'categories_total_count',
            'categories_active_count',
            'category_stats',
            'category_tree',
            'featured_categories'
        ];
        
        foreach ($statsKeys as $key) {
            Cache::forget($key);
        }
        
        // Clear filtered categories cache
        // Since we don't know the exact hash, we need to clear all filtered categories
        // by using pattern matching
        try {
            $cacheStore = Cache::getStore();
            
            // For database cache, we'll clear by specific known cache keys
            if ($cacheStore instanceof \Illuminate\Cache\DatabaseStore) {
                $table = config('cache.stores.database.table', 'cache');
                $prefix = config('cache.prefix', 'laravel_cache');
                
                // Get all cache keys that start with 'categories_filtered_'
                $filteredKeys = DB::table($table)
                    ->where('key', 'like', $prefix . '_categories_filtered_%')
                    ->pluck('key')
                    ->map(function($key) use ($prefix) {
                        return str_replace($prefix . '_', '', $key);
                    })
                    ->toArray();
                
                foreach ($filteredKeys as $key) {
                    Cache::forget($key);
                }
            }
            // For Redis, we can use pattern matching
            elseif (method_exists($cacheStore, 'getRedis')) {
                $redis = $cacheStore->getRedis();
                $prefix = config('cache.prefix', 'laravel_cache');
                $pattern = $prefix . '_categories_filtered_*';
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            }
            // For other stores, skip filtered cache clearing
        } catch (\Exception $e) {
            Log::warning('Failed to clear filtered categories cache', [
                'error' => $e->getMessage()
            ]);
        }
        
        // Clear search cache
        $searchPattern = 'category_search_*';
        try {
            $cacheStore = Cache::getStore();
            
            if ($cacheStore instanceof \Illuminate\Cache\DatabaseStore) {
                $table = config('cache.stores.database.table', 'cache');
                $prefix = config('cache.prefix', 'laravel_cache');
                
                $searchKeys = DB::table($table)
                    ->where('key', 'like', $prefix . '_category_search_%')
                    ->pluck('key')
                    ->map(function($key) use ($prefix) {
                        return str_replace($prefix . '_', '', $key);
                    })
                    ->toArray();
                
                foreach ($searchKeys as $key) {
                    Cache::forget($key);
                }
            }
            elseif (method_exists($cacheStore, 'getRedis')) {
                $redis = $cacheStore->getRedis();
                $prefix = config('cache.prefix', 'laravel_cache');
                $pattern = $prefix . '_category_search_*';
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to clear category search cache', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all brand-related caches
     */
    public function clearBrandCaches($brandId = null)
    {
        $tags = ['brands'];
        
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tags)->flush();
        } else {
            if ($brandId) {
                Cache::forget("brand_{$brandId}");
            }
            
            $patterns = ['brands_list_*', 'brands_*_page_*', 'brands_featured_*'];
            foreach ($patterns as $pattern) {
                $this->clearCacheByPattern($pattern);
            }
        }
    }

    /**
     * Clear all banner-related caches
     */
    public function clearBannerCaches($bannerId = null)
    {
        $tags = ['banners'];
        
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tags)->flush();
        } else {
            if ($bannerId) {
                Cache::forget("banner_{$bannerId}");
            }
            
            $patterns = ['banners_list_*', 'active_banners_*', 'banners_position_*'];
            foreach ($patterns as $pattern) {
                $this->clearCacheByPattern($pattern);
            }
        }
    }

    /**
     * Preload frequently accessed data
     */
    public function preloadData()
    {
        try {
            // Preload categories tree
            $this->cacheCollection('categories_tree', function() {
                return \App\Models\Category::with('children.children')
                    ->whereNull('parent_id')
                    ->active()
                    ->ordered()
                    ->get();
            }, self::CATEGORY_CACHE_TTL, ['categories']);
            
            // Preload featured brands
            $this->cacheCollection('brands_featured', function() {
                return \App\Models\Brand::active()
                    ->featured()
                    ->ordered()
                    ->get();
            }, self::BRAND_CACHE_TTL, ['brands']);
            
            // Preload active banners
            $this->cacheCollection('banners_active', function() {
                return \App\Models\Banner::active()
                    ->currentTime()
                    ->ordered()
                    ->get();
            }, self::BANNER_CACHE_TTL, ['banners']);
            
            // Preload filter options
            $this->cacheCollection('filter_options', function() {
                return [
                    'categories' => \App\Models\Category::active()->orderBy('category_name')->get(['category_id', 'category_name']),
                    'brands' => \App\Models\Brand::active()->orderBy('brand_name')->get(['brand_id', 'brand_name']),
                    'price_ranges' => $this->getDynamicPriceRanges()
                ];
            }, self::PRODUCT_CACHE_TTL, ['products', 'filters']);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Cache preload failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get dynamic price ranges based on actual product prices
     */
    private function getDynamicPriceRanges()
    {
        try {
            $prices = DB::table('products')
                ->where('active', 1)
                ->pluck('price')
                ->filter()
                ->sort()
                ->values();
                
            if ($prices->isEmpty()) {
                return [
                    ['label' => 'Under ₹500', 'min' => 0, 'max' => 500],
                    ['label' => '₹500 - ₹1,000', 'min' => 500, 'max' => 1000],
                    ['label' => '₹1,000 - ₹2,500', 'min' => 1000, 'max' => 2500],
                    ['label' => '₹2,500 - ₹5,000', 'min' => 2500, 'max' => 5000],
                    ['label' => '₹5,000+', 'min' => 5000, 'max' => null],
                ];
            }
            
            $minPrice = floor($prices->first() / 100) * 100;
            $maxPrice = ceil($prices->last() / 500) * 500;
            
            $ranges = [];
            $steps = 6;
            $stepSize = ($maxPrice - $minPrice) / $steps;
            $stepSize = max(500, $stepSize);
            
            for ($i = 0; $i < $steps; $i++) {
                $startPrice = $minPrice + ($i * $stepSize);
                $endPrice = $startPrice + $stepSize;
                
                if ($i === $steps - 1) {
                    $endPrice = null; // Last range is "and above"
                }
                
                $label = $endPrice 
                    ? "₹" . number_format($startPrice) . " - ₹" . number_format($endPrice)
                    : "₹" . number_format($startPrice) . "+";
                
                $ranges[] = [
                    'label' => $label,
                    'min' => $startPrice,
                    'max' => $endPrice
                ];
            }
            
            return $ranges;
        } catch (\Exception $e) {
            Log::error('Dynamic price ranges generation failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get cache statistics
     */
    public function getCacheStatistics()
    {
        try {
            $stats = [];
            
            if (config('cache.default') === 'redis') {
                $redis = Cache::getStore()->getRedis();
                $info = $redis->info('memory');
                
                $stats = [
                    'memory_usage' => $info['used_memory_human'] ?? 'N/A',
                    'memory_peak' => $info['used_memory_peak_human'] ?? 'N/A',
                    'connected_clients' => $info['connected_clients'] ?? 'N/A',
                ];
            }
            
            // Add Laravel cache specific stats
            $stats['driver'] = config('cache.default');
            $stats['prefix'] = config('cache.prefix');
            $stats['store'] = config('cache.stores.' . config('cache.default') . '.driver', 'N/A');
            
            return $stats;
        } catch (\Exception $e) {
            return ['error' => 'Unable to retrieve cache statistics'];
        }
    }

    /**
     * Warm up cache for better performance
     */
    public function warmUpCache()
    {
        try {
            $startTime = microtime(true);
            
            // Get most popular products
            $this->cacheCollection('products_popular', function() {
                return \App\Models\Product::active()
                    ->withStock()
                    ->with(['category', 'brand'])
                    ->orderBy('view_count', 'desc')
                    ->limit(20)
                    ->get();
            }, self::PRODUCT_CACHE_TTL, ['products']);
            
            // Get featured products (removed - is_featured column doesn't exist in products table)
            $this->cacheCollection('products_featured', function() {
                return \App\Models\Product::active()
                    ->with(['category', 'brand'])
                    ->orderBy('view_count', 'desc')
                    ->limit(8)
                    ->get();
            }, self::PRODUCT_CACHE_TTL, ['products']);
            
            $executionTime = round(microtime(true) - $startTime, 2);
            
            return [
                'success' => true,
                'execution_time' => $executionTime,
                'message' => 'Cache warmed up successfully'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Cache warm-up failed'
            ];
        }
    }

    /**
     * Clear cache by pattern (fallback method)
     */
    private function clearCacheByPattern($pattern)
    {
        try {
            $cacheStore = Cache::getStore();
            
            // For database cache store, pattern matching is inefficient
            // Since the regular cache operations are working, we'll skip this
            if ($cacheStore instanceof \Illuminate\Cache\DatabaseStore) {
                Log::info('Pattern cache clearing skipped for database store', ['pattern' => $pattern]);
                return;
            }
            
            // Handle Redis cache store
            if (method_exists($cacheStore, 'getRedis')) {
                $redis = $cacheStore->getRedis();
                $prefix = config('cache.prefix', 'laravel_cache');
                
                // Get all keys matching the pattern
                $pattern_with_prefix = $prefix . '_' . $pattern;
                $keys = $redis->keys($pattern_with_prefix);
                
                foreach ($keys as $key) {
                    $redis->del($key);
                }
            }
            // Handle other cache stores
            else {
                // For stores that don't support pattern matching, we can't implement this efficiently
                // Log a warning but don't throw an error
                Log::warning('Cache pattern clearing not supported for current cache driver', [
                    'driver' => get_class($cacheStore),
                    'pattern' => $pattern
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Cache pattern clearing failed', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Flush all application cache
     */
    public function flushAll()
    {
        try {
            Cache::flush();
            return true;
        } catch (\Exception $e) {
            Log::error('Cache flush failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}