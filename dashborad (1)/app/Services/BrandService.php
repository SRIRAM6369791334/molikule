<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandService
{
    public function getTotalBrands()
    {
        return Brand::count();
    }

    public function getTotalActiveBrands()
    {
        return Brand::where('is_active', 1)->count();
    }

    public function updateBrandStatus($id, $status)
    {
        $brand = Brand::findOrFail($id);
        $brand->is_active = $status;
        $brand->save();
        return $brand;
    }

    public function getFilteredBrands(array $filters, $perPage = 16)
    {
        $query = Brand::withCount('products');

        // Search filter
        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where('brand_name', 'like', '%' . $searchTerm . '%');
        }

        // Status filter - temporarily disabled until migrations are run
        if (isset($filters['status']) && $filters['status'] !== '') {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Has products filter
        if (isset($filters['has_products']) && $filters['has_products'] !== '') {
            if ($filters['has_products'] === 'with_products') {
                $query->having('products_count', '>', 0);
            } elseif ($filters['has_products'] === 'without_products') {
                $query->having('products_count', '=', 0);
            }
        }

        // Sort order
        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            switch($filters['sort_by']) {
                case 'name':
                    $query->orderBy('brand_name', $direction);
                    break;
                case 'products':
                    $query->orderBy('products_count', $direction);
                    break;
                case 'date':
                    $query->orderBy('created_at', $direction);
                    break;
                default:
                    $query->orderBy('brand_name', 'asc');
            }
        } else {
            $query;
        }

        return $query->paginate($perPage);
    }

    /**
     * Get featured brands
     */
    public function getFeaturedBrands($limit = 10)
    {
        return Brand::where('is_featured', 1)
                   ->where('is_active', 1)
                   ->orderBy('brand_name')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get brands with categories and products
     */
    public function getBrandsWithCategories($limit = 20)
    {
        return Brand::with(['products' => function($query) {
                $query->where('active', 1)->limit(5);
            }])
            ->where('is_active', 1)
            ->orderBy('brand_name')
            ->limit($limit)
            ->get();
    }

    /**
     * Update brand product count
     */
    public function updateBrandProductCounts()
    {
        $brands = Brand::all();

        foreach ($brands as $brand) {
            $count = $brand->products()->where('active', 1)->count();
            // If you have a product_count column, uncomment the next line
            // $brand->update(['product_count' => $count]);
        }
    }

    /**
     * Clear brand caches
     */
    public function clearBrandCaches()
    {
        $cacheKeys = [
            'brands_total_count',
            'brands_active_count',
            'brands_stats',
            'featured_brands',
            'brands_with_categories'
        ];

        foreach ($cacheKeys as $key) {
            \Illuminate\Support\Facades\Cache::forget($key);
        }
    }

    /**
     * Get brand analytics
     */
    public function getBrandAnalytics($brandId, $days = 30)
    {
        $brand = Brand::find($brandId);
        if (!$brand) {
            return null;
        }

        return [
            'brand_id' => $brandId,
            'brand_name' => $brand->brand_name,
            'total_products' => $brand->products()->where('active', 1)->count(),
            'total_views' => $brand->view_count ?? 0,
            'avg_price' => $brand->products()->where('active', 1)->avg('price'),
            'featured_products' => $brand->products()->where('active', 1)->where('priority', 'high')->count(),
            'last_updated' => now()
        ];
    }

    /**
     * Get top performing brands
     */
    public function getTopBrands($limit = 10, $days = 30)
    {
        return Brand::where('is_active', 1)
                   ->withCount(['products' => function($query) {
                       $query->where('active', 1);
                   }])
                   ->orderBy('products_count', 'desc')
                   ->orderBy('brand_name')
                   ->limit($limit)
                   ->get();
    }

    public function searchBrands(string $query, int $limit = 10)
    {
        return Brand::where('brand_name', 'like', "%{$query}%")
                   ->where('is_active', 1)
                   ->take($limit)
                   ->get(['brand_id', 'brand_name']);
    }

    /**
     * Get brands for dropdown/filtering
     */
    public function getBrandsForSelect($includeInactive = false)
    {
        $query = Brand::orderBy('sort_order')->orderBy('brand_name');

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->get(['brand_id', 'brand_name']);
    }

    public function getBrandsWithStats()
    {
        // Get comprehensive brand statistics
        $stats = \DB::select("
            SELECT
                COUNT(*) as total_brands,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_brands,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_brands,
                AVG(COALESCE(p.products_count, 0)) as avg_products_per_brand,
                MAX(COALESCE(p.products_count, 0)) as max_products_in_brand
            FROM brands b
            LEFT JOIN (
                SELECT brand_id, COUNT(*) as products_count
                FROM products
                WHERE active = 1
                GROUP BY brand_id
            ) p ON b.brand_id = p.brand_id
        ")[0];

        return [
            'total_brands' => (int) $stats->total_brands,
            'active_brands' => (int) $stats->active_brands,
            'inactive_brands' => (int) $stats->inactive_brands,
            'avg_products_per_brand' => round((float) $stats->avg_products_per_brand, 2),
            'max_products_in_brand' => (int) $stats->max_products_in_brand,
        ];
    }

    public function bulkUpdateBrands(array $brandIds, array $updateData)
    {
        $brands = Brand::whereIn('brand_id', $brandIds)->get();

        $updated = 0;
        foreach ($brands as $brand) {
            $brand->update($updateData);
            $updated++;
        }

        return $updated;
    }

    public function bulkActivateBrands(array $brandIds)
    {
        return $this->bulkUpdateBrands($brandIds, ['is_active' => true]);
    }

    public function bulkDeactivateBrands(array $brandIds)
    {
        return $this->bulkUpdateBrands($brandIds, ['is_active' => false]);
    }

    public function bulkDeleteBrands(array $brandIds)
    {
        $brands = Brand::withCount('products')
                      ->whereIn('brand_id', $brandIds)
                      ->get();

        $deleted = 0;
        $skipped = 0;

        foreach ($brands as $brand) {
            if ($brand->products_count > 0) {
                $skipped++;
                continue;
            }

            // Delete brand logo if exists
            $logoPath = $brand->getRawOriginal('logo');
            if ($logoPath && \Storage::disk('uploads')->exists($logoPath)) {
                \Storage::disk('uploads')->delete($logoPath);
            }

            $brand->delete();
            $deleted++;
        }

        return ['deleted' => $deleted, 'skipped' => $skipped];
    }

}
