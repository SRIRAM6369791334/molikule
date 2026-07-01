<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductService
{
    // Cache configuration
    const CACHE_TTL_PRODUCTS = 3600; // 1 hour
    const CACHE_TTL_STATS = 1800; // 30 minutes
    const CACHE_TTL_FILTERS = 900; // 15 minutes

    /**
     * Enhanced product retrieval with caching
     */
    public function getTotalProducts(): int
    {
        return Cache::remember('products_total_count', self::CACHE_TTL_STATS, function () {
            return Product::count();
        });
    }

    public function getTotalActiveProducts(): int
    {
        return Cache::remember('products_active_count', self::CACHE_TTL_STATS, function () {
            return Product::active()->count();
        });
    }

    /**
     * Update product with cache invalidation
     */
    public function updateProductStatus($id, $status): Product
    {
        $product = Product::findOrFail($id);
        $product->active = $status;
        $product->save();
        
        // Clear related caches
        $this->clearProductCaches($id);
        $this->clearProductListCaches();
        
        return $product;
    }

    /**
     * Enhanced stock management - real-time for accuracy
     */
    public function getLowStockCount($threshold = 15)
    {
        return Product::lowStock($threshold)->count();
    }

    public function getLowStockProducts($threshold = 15)
    {
        return Product::lowStock($threshold)
            ->with(['category', 'brand'])
            ->get();
    }

    /**
     * Enhanced bulk operations
     */
    public function bulkUpdateProducts(array $updates)
    {
        DB::beginTransaction();
        try {
            foreach ($updates as $id => $data) {
                $product = Product::findOrFail($id);
                $product->update($data);
                $this->clearProductCaches($id);
            }
            DB::commit();
            
            // Clear all related caches
            $this->clearProductListCaches();
            $this->clearProductStatsCache();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Enhanced filtered products - real-time for stock accuracy
     */
    public function getFilteredProducts(array $filters, $perPage = 12)
    {
        $query = Product::with(['category', 'brand', 'variants'])
                       ->select(['products.*'])
                       ->distinct();

        // Enhanced filters
        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Enhanced filter statistics - completely real-time (no caching)
     */
    public function getFilterStats()
    {
        $lowStockThreshold = config('inventory.low_stock_threshold', 15);

        $stats = DB::table('products')
            ->selectRaw('COUNT(*) as total_products')
            ->selectRaw('SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) as active_products')
            ->selectRaw('SUM(CASE WHEN active = 0 THEN 1 ELSE 0 END) as inactive_products')
            ->selectRaw('SUM(CASE WHEN track_quantity = 0 OR (track_quantity = 1 AND stock_quantity > ?) THEN 1 ELSE 0 END) as in_stock_count', [$lowStockThreshold])
            ->selectRaw('SUM(CASE WHEN track_quantity = 1 AND stock_quantity > 0 AND stock_quantity <= ? THEN 1 ELSE 0 END) as low_stock_count', [$lowStockThreshold])
            ->selectRaw('SUM(CASE WHEN track_quantity = 1 AND stock_quantity <= 0 AND continue_selling_when_out_of_stock = 0 THEN 1 ELSE 0 END) as out_of_stock_count')
            ->selectRaw('ROUND(AVG(mrp_price), 2) as average_price')
            ->selectRaw('MIN(mrp_price) as min_price')
            ->selectRaw('MAX(mrp_price) as max_price')
            ->first();

        return [
            'total_products' => (int) $stats->total_products,
            'active_products' => (int) $stats->active_products,
            'inactive_products' => (int) $stats->inactive_products,
            'in_stock_count' => (int) $stats->in_stock_count,
            'low_stock_count' => (int) $stats->low_stock_count,
            'out_of_stock_count' => (int) $stats->out_of_stock_count,
            'average_price' => (float) $stats->average_price,
            'min_price' => (float) $stats->min_price,
            'max_price' => (float) $stats->max_price,
        ];
    }

    /**
     * Enhanced advanced search - real-time for stock accuracy
     */
    public function advancedSearch(array $criteria, $perPage = 12)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        // Apply advanced search criteria
        if (isset($criteria['query'])) {
            $query->advancedSearch($criteria);
        }

        if (isset($criteria['category_ids']) && !empty($criteria['category_ids'])) {
            $query->whereIn('category_id', $criteria['category_ids']);
        }

        if (isset($criteria['brand_ids']) && !empty($criteria['brand_ids'])) {
            $query->whereIn('brand_id', $criteria['brand_ids']);
        }

        if (isset($criteria['price_min']) || isset($criteria['price_max'])) {
            $query->byPriceRange($criteria['price_min'] ?? null, $criteria['price_max'] ?? null);
        }

        if (isset($criteria['tags']) && !empty($criteria['tags'])) {
            $query->byTags($criteria['tags']);
        }

        if (isset($criteria['in_stock'])) {
            if ($criteria['in_stock']) {
                $query->withStock();
            } else {
                $query->where('track_quantity', true)
                      ->where('stock_quantity', '<=', 0)
                      ->where('continue_selling_when_out_of_stock', false);
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * Cache management methods
     */
    public function clearProductCaches($productId)
    {
        $cacheKeys = [
            "product_{$productId}",
            "product_stock_{$productId}",
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    public function clearProductListCaches()
    {
        // Note: Pattern clearing doesn't work with database store, so we only clear specific known keys
        // For database store, only exact key matching works, not wildcards
        Cache::forget('featured_products');
        Cache::forget('product_filter_options');

        // Clear stats cache too
        $this->clearProductStatsCache();

        // Individual filtered product caches will expire naturally
        // In production with Redis, we could implement cache tags for better clearing
    }

    private function clearProductStatsCache()
    {
        $cacheKeys = [
            'products_total_count',
            'products_active_count',
            'product_filter_stats',
            'product_static_stats', // Clear static stats cache too
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Cache key generation
     */
    private function generateFiltersCacheKey(array $filters, $perPage)
    {
        $key = 'products_filtered_' . md5(serialize($filters) . $perPage);
        return $key;
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, array $filters)
    {
        // Category filter
        if (isset($filters['category']) && $filters['category']) {
            $query->where('category_id', $filters['category']);
        }

        // Brand filter
        if (isset($filters['brand']) && $filters['brand']) {
            $query->where('brand_id', $filters['brand']);
        }

        // Enhanced stock status filter
        if (isset($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'in_stock':
                    $query->withStock();
                    break;
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
            }
        }

        // Enhanced search - only use columns that definitely exist in core tables
        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where(function($q) use ($searchTerm) {
                // Core product columns that always exist
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('mrp_price', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($sq) use ($searchTerm) {
                      $sq->where('category_name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('brand', function($sq) use ($searchTerm) {
                      $sq->where('brand_name', 'like', '%' . $searchTerm . '%');
                  });

                // Optional columns - only search if they exist in the database
                if (\Schema::hasColumn('products', 'description')) {
                    $q->orWhere('description', 'like', '%' . $searchTerm . '%');
                }
                if (\Schema::hasColumn('products', 'short_description')) {
                    $q->orWhere('short_description', 'like', '%' . $searchTerm . '%');
                }
                if (\Schema::hasColumn('products', 'barcode')) {
                    $q->orWhere('barcode', 'like', '%' . $searchTerm . '%');
                }
                if (\Schema::hasColumn('products', 'tags')) {
                    $q->orWhereJsonContains('tags', $searchTerm);
                }
                // Search by product_id as well
                $q->orWhere('product_id', 'like', '%' . $searchTerm . '%');
            });
        }

        // Price range filter
        if (isset($filters['price_min']) && $filters['price_min']) {
            $query->where('mrp_price', '>=', $filters['price_min']);
        }
        if (isset($filters['price_max']) && $filters['price_max']) {
            $query->where('mrp_price', '<=', $filters['price_max']);
        }

        // Date range filters
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('active', $filters['status'] === 'active' ? 1 : 0);
        }

        // Tags filter
        if (isset($filters['tags']) && !empty($filters['tags'])) {
            $query->byTags($filters['tags']);
        }

        // Featured products filter (removed - is_featured column doesn't exist in products table)
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query, array $filters)
    {
        if (isset($filters['sort_by']) && $filters['sort_by']) {
            $direction = $filters['sort_direction'] ?? 'asc';
            $sortBy = $filters['sort_by'];

            switch($sortBy) {
                case 'name':
                    $query->orderBy('name', $direction);
                    break;
                case 'price':
                    $query->orderBy('mrp_price', $direction);
                    break;
                case 'stock':
                    $query->orderBy('stock_quantity', $direction);
                    break;
                case 'date':
                    $query->orderBy('created_at', $direction);
                    break;
                case 'category':
                    $query->join('categories', 'products.category_id', '=', 'categories.category_id')
                          ->orderBy('categories.category_name', $direction)
                          ->select('products.*');
                    break;
                case 'brand':
                    $query->join('brands', 'products.brand_id', '=', 'brands.brand_id')
                          ->orderBy('brands.brand_name', $direction)
                          ->select('products.*');
                    break;
                case 'popularity':
                    $query->orderBy('view_count', $direction);
                    break;
                case 'rating':
                    $query->orderBy('average_rating', $direction);
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
    }

    /**
     * Preload frequently accessed data
     */
    public function preloadData()
    {
        // Preload products statistics
        $this->getFilterStats();
        
        // Preload low stock products
        $this->getLowStockProducts();
        
        // Preload featured products (removed - is_featured column doesn't exist in products table)
        // Cache::remember('featured_products', self::CACHE_TTL_PRODUCTS, function () {
        //     return Product::featured()
        //         ->with(['category', 'brand'])
        //         ->limit(10)
        //         ->get();
        // });
        
        // Preload filter options
        Cache::remember('product_filter_options', self::CACHE_TTL_FILTERS, function () {
            return [
                'categories' => \App\Models\Category::active()
                    ->orderBy('category_name')
                    ->get(['category_id', 'category_name']),
                'brands' => \App\Models\Brand::active()
                    ->orderBy('brand_name')
                    ->get(['brand_id', 'brand_name']),
                'price_ranges' => $this->getPriceRanges(),
            ];
        });
    }

    /**
     * Get price ranges for filters
     */
    private function getPriceRanges()
    {
        $products = Product::active()->pluck('mrp_price');
        if ($products->isEmpty()) {
            return [];
        }
        
        $minPrice = floor($products->min() / 100) * 100;
        $maxPrice = ceil($products->max() / 500) * 500;
        
        $ranges = [];
        $step = max(500, ($maxPrice - $minPrice) / 10);
        
        for ($price = $minPrice; $price < $maxPrice; $price += $step) {
            $nextPrice = $price + $step;
            $ranges[] = [
                'label' => "₹" . number_format($price) . " - ₹" . number_format($nextPrice),
                'min' => $price,
                'max' => $nextPrice
            ];
        }
        
        return $ranges;
    }

    /**
     * Backward compatibility methods
     */
    public function bulkUpdate(array $updates)
    {
        return $this->bulkUpdateProducts($updates);
    }

    public function getStatusCounts()
    {
        $stats = $this->getFilterStats();
        
        return [
            'total' => $stats['total_products'],
            'active' => $stats['active_products'],
            'inactive' => $stats['inactive_products'],
            'in_stock' => $stats['in_stock_count'],
            'low_stock' => $stats['low_stock_count'],
            'out_of_stock' => $stats['out_of_stock_count'],
        ];
    }
}
