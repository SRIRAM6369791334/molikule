<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    protected $cacheTTL = 3600; // 1 hour

    /**
     * Track product view
     */
    public function trackProductView($productId, $userId = null)
    {
        try {
            $product = Product::find($productId);
            if (!$product) {
                return false;
            }

            // Increment view count
            $product->increment('view_count');
            
            // Track in cache for real-time analytics
            $this->incrementCacheCounter('product_views_' . $productId);
            
            // Store view event for detailed analytics
            $this->logEvent('product_view', [
                'product_id' => $productId,
                'user_id' => $userId,
                'timestamp' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Product view tracking failed', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Track category view
     */
    public function trackCategoryView($categoryId, $userId = null)
    {
        try {
            $category = Category::find($categoryId);
            if (!$category) {
                return false;
            }

            $category->increment('view_count');
            $this->incrementCacheCounter('category_views_' . $categoryId);
            
            $this->logEvent('category_view', [
                'category_id' => $categoryId,
                'user_id' => $userId,
                'timestamp' => now(),
                'ip_address' => request()->ip()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Category view tracking failed', [
                'category_id' => $categoryId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Track brand view
     */
    public function trackBrandView($brandId, $userId = null)
    {
        try {
            $brand = Brand::find($brandId);
            if (!$brand) {
                return false;
            }

            $brand->increment('view_count');
            $this->incrementCacheCounter('brand_views_' . $brandId);
            
            $this->logEvent('brand_view', [
                'brand_id' => $brandId,
                'user_id' => $userId,
                'timestamp' => now(),
                'ip_address' => request()->ip()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Brand view tracking failed', [
                'brand_id' => $brandId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Track banner impression
     */
    public function trackBannerImpression($bannerId, $userId = null)
    {
        try {
            $banner = Banner::find($bannerId);
            if (!$banner) {
                return false;
            }

            $banner->increment('impression_count');
            $this->incrementCacheCounter('banner_impressions_' . $bannerId);
            
            $this->logEvent('banner_impression', [
                'banner_id' => $bannerId,
                'user_id' => $userId,
                'timestamp' => now(),
                'ip_address' => request()->ip()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Banner impression tracking failed', [
                'banner_id' => $bannerId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Track banner click
     */
    public function trackBannerClick($bannerId, $userId = null, $targetUrl = null)
    {
        try {
            $banner = Banner::find($bannerId);
            if (!$banner) {
                return false;
            }

            $banner->increment('click_count');
            $this->incrementCacheCounter('banner_clicks_' . $bannerId);
            
            $this->logEvent('banner_click', [
                'banner_id' => $bannerId,
                'user_id' => $userId,
                'target_url' => $targetUrl,
                'timestamp' => now(),
                'ip_address' => request()->ip()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Banner click tracking failed', [
                'banner_id' => $bannerId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get product analytics
     */
    public function getProductAnalytics($productId, $days = 30)
    {
        return Cache::remember("product_analytics_{$productId}_{$days}", $this->cacheTTL, function() use ($productId, $days) {
            $product = Product::find($productId);
            if (!$product) {
                return null;
            }

            $dateFrom = now()->subDays($days)->startOfDay();
            
            // Get cached daily views
            $dailyViews = $this->getCacheCounterHistory("product_views_{$productId}", $days);
            
            // Calculate metrics
            $totalViews = $product->view_count;
            $avgDailyViews = $totalViews > 0 ? round($totalViews / $days, 2) : 0;
            $trendPercentage = $this->calculateTrend($dailyViews);
            
            return [
                'product_id' => $productId,
                'total_views' => $totalViews,
                'avg_daily_views' => $avgDailyViews,
                'trend_percentage' => $trendPercentage,
                'daily_views' => $dailyViews,
                'last_updated' => now()
            ];
        });
    }

    /**
     * Get top performing products
     */
    public function getTopProducts($limit = 10, $days = 30)
    {
        return Cache::remember("top_products_{$limit}_{$days}", $this->cacheTTL, function() use ($limit, $days) {
            return Product::where('active', 1)
                ->select('product_id', 'name', 'mrp_price', 'view_count')
                ->orderBy('view_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get category analytics
     */
    public function getCategoryAnalytics($categoryId, $days = 30)
    {
        return Cache::remember("category_analytics_{$categoryId}_{$days}", $this->cacheTTL, function() use ($categoryId, $days) {
            $category = Category::find($categoryId);
            if (!$category) {
                return null;
            }

            return [
                'category_id' => $categoryId,
                'category_name' => $category->category_name,
                'total_views' => $category->view_count,
                'products_count' => $category->products()->where('active', 1)->count(),
                'avg_price' => $category->products()->where('active', 1)->avg('mrp_price'),
                'last_updated' => now()
            ];
        });
    }

    /**
     * Get brand analytics
     */
    public function getBrandAnalytics($brandId, $days = 30)
    {
        return Cache::remember("brand_analytics_{$brandId}_{$days}", $this->cacheTTL, function() use ($brandId, $days) {
            $brand = Brand::find($brandId);
            if (!$brand) {
                return null;
            }

            return [
                'brand_id' => $brandId,
                'brand_name' => $brand->brand_name,
                'total_views' => $brand->view_count,
                'products_count' => $brand->products()->where('active', 1)->count(),
                'avg_price' => $brand->products()->where('active', 1)->avg('mrp_price'),
                'last_updated' => now()
            ];
        });
    }

    /**
     * Get banner performance analytics
     */
    public function getBannerPerformance($bannerId, $days = 30)
    {
        return Cache::remember("banner_performance_{$bannerId}_{$days}", $this->cacheTTL, function() use ($bannerId, $days) {
            $banner = Banner::find($bannerId);
            if (!$banner) {
                return null;
            }

            $impressions = $banner->impression_count;
            $clicks = $banner->click_count;
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

            return [
                'banner_id' => $bannerId,
                'title' => $banner->title,
                'impressions' => $impressions,
                'clicks' => $clicks,
                'ctr' => $ctr,
                'position' => $banner->position,
                'is_active' => $banner->is_active,
                'last_updated' => now()
            ];
        });
    }

    /**
     * Get banner analytics (alias for getBannerPerformance)
     */
    public function getBannerAnalytics($bannerId, $days = 30)
    {
        return $this->getBannerPerformance($bannerId, $days);
    }

    /**
     * Get overall analytics dashboard data
     */
    public function getDashboardAnalytics()
    {
        return Cache::remember('dashboard_analytics', 1800, function() { // 30 minutes
            return [
                'products' => [
                    'total' => Product::count(),
                    'active' => Product::where('active', 1)->count(),
                    'total_views' => Product::sum('view_count'),
                    'avg_price' => round(Product::avg('mrp_price'), 2)
                ],
                'categories' => [
                    'total' => Category::count(),
                    'active' => Category::where('is_active', 1)->count(),
                    'total_views' => Category::sum('view_count')
                ],
                'brands' => [
                    'total' => Brand::count(),
                    'active' => Brand::where('is_active', 1)->count(),
                    'total_views' => Brand::sum('view_count')
                ],
                'banners' => [
                    'total' => Banner::count(),
                    'active' => Banner::where('is_active', 1)->count(),
                    'total_impressions' => Banner::sum('impression_count'),
                    'total_clicks' => Banner::sum('click_count')
                ],
                'top_products' => $this->getTopProducts(5, 30),
                'recent_activity' => $this->getRecentActivity(),
                'last_updated' => now()
            ];
        });
    }

    /**
     * Log analytics event
     */
    private function logEvent($eventType, $data)
    {
        // In a real implementation, you might store these in a database table
        // For now, we'll just log them
        Log::info("Analytics Event: {$eventType}", $data);
    }

    /**
     * Increment cache counter
     */
    private function incrementCacheCounter($key)
    {
        $cacheKey = "analytics_counter_{$key}";
        Cache::increment($cacheKey);
    }

    /**
     * Get cache counter history
     */
    private function getCacheCounterHistory($key, $days)
    {
        $history = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $cacheKey = "analytics_daily_{$key}_{$date}";
            $history[$date] = Cache::get($cacheKey, 0);
        }
        return $history;
    }

    /**
     * Calculate trend percentage
     */
    private function calculateTrend($dailyData)
    {
        if (count($dailyData) < 2) {
            return 0;
        }

        $values = array_values($dailyData);
        $firstHalf = array_slice($values, 0, floor(count($values) / 2));
        $secondHalf = array_slice($values, floor(count($values) / 2));
        
        $firstAvg = array_sum($firstHalf) / count($firstHalf);
        $secondAvg = array_sum($secondHalf) / count($secondHalf);
        
        if ($firstAvg == 0) {
            return $secondAvg > 0 ? 100 : 0;
        }
        
        return round((($secondAvg - $firstAvg) / $firstAvg) * 100, 2);
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity()
    {
        // This would typically query an activity log table
        // For now, return sample data
        return [
            'new_products' => Product::where('created_at', '>=', now()->subDays(7))->count(),
            'new_categories' => Category::where('created_at', '>=', now()->subDays(7))->count(),
            'new_brands' => Brand::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Clear analytics cache
     */
    public function clearAnalyticsCache($pattern = null)
    {
        $patterns = [
            'product_analytics_*',
            'category_analytics_*', 
            'brand_analytics_*',
            'banner_performance_*',
            'top_products_*',
            'dashboard_analytics'
        ];
        
        if ($pattern) {
            $patterns = [$pattern];
        }
        
        foreach ($patterns as $pattern) {
            $this->clearCacheByPattern($pattern);
        }
    }

    /**
     * Clear cache by pattern
     */
    private function clearCacheByPattern($pattern)
    {
        // This would require access to the cache store's keys
        // Implementation depends on the cache driver used
        Cache::forget($pattern);
    }
}