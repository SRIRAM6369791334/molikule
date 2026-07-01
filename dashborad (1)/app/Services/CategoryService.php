<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    /**
     * Get total categories count
     */
    public function getTotalCategories()
    {
        return Category::count();
    }

    /**
     * Get total active categories count
     */
    public function getTotalActiveCategories()
    {
        return Category::active()->count();
    }

    /**
     * Update category status
     */
    public function updateCategoryStatus($id, $status)
    {
        $category = Category::findOrFail($id);
        $category->is_active = $status;
        $category->save();
        
        return $category;
    }

    /**
     * Get filtered categories
     */
    public function getFilteredCategories(array $filters, $perPage = 16)
    {
        $query = Category::with(['parent', 'products'])
                       ->withCount('products');

        // Apply filters
        $this->applyCategoryFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Get categories with statistics
     */
    public function getCategoriesWithStats()
    {
        $stats = DB::select("
            SELECT
                COUNT(*) as total_categories,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_categories,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_categories,
                SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_categories,
                AVG(p_count) as avg_products_per_category,
                MAX(p_count) as max_products_in_category,
                0 as total_views
            FROM (
                SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.deleted_at IS NULL) as p_count
                FROM categories c
            ) as stats
        ")[0];


        return [
            'total_categories' => (int) $stats->total_categories,
            'active_categories' => (int) $stats->active_categories,
            'inactive_categories' => (int) $stats->inactive_categories,
            'featured_categories' => (int) $stats->featured_categories,
            'avg_products_per_category' => round((float) $stats->avg_products_per_category, 2),
            'max_products_in_category' => (int) $stats->max_products_in_category,
            'total_views' => (int) $stats->total_views,
        ];
    }

    /**
     * Bulk operations
     */
    public function bulkUpdateCategories(array $categoryIds, array $updateData)
    {
        $updated = Category::whereIn('category_id', $categoryIds)->update($updateData);
        return $updated;
    }

    public function bulkActivateCategories(array $categoryIds)
    {
        return $this->bulkUpdateCategories($categoryIds, ['is_active' => true]);
    }

    public function bulkDeactivateCategories(array $categoryIds)
    {
        return $this->bulkUpdateCategories($categoryIds, ['is_active' => false]);
    }

    public function bulkDeleteCategories(array $categoryIds)
    {
        $categories = Category::withCount('products')
                            ->whereIn('category_id', $categoryIds)
                            ->get();

        $deleted = 0;
        $skipped = 0;
        
        foreach ($categories as $category) {
            if ($category->products_count > 0 || $category->children()->count() > 0) {
                $skipped++;
                continue;
            }

            $category->delete();
            $deleted++;
        }
        
        return ['deleted' => $deleted, 'skipped' => $skipped];
    }

    /**
     * Search categories
     */
    public function searchCategories(string $query, int $limit = 10)
    {
        return Category::search($query)
                      ->limit($limit)
                      ->get(['category_id', 'category_name', 'description']);
    }

    /**
     * Get categories for dropdown
     */
    public function getCategoriesForSelect($includeInactive = false, $parentId = null)
    {
        $query = Category::orderBy('category_name');

        if (!$includeInactive) {
            $query->active();
        }

        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        }

        return $query->get(['category_id', 'category_name']);
    }

    /**
     * Get featured categories
     */
    public function getFeaturedCategories()
    {
        return Category::getFeaturedCategories();
    }

    /**
     * Set featured categories
     */
    public function setFeaturedCategories(array $categoryIds)
    {
        // First unset all featured categories
        Category::where('is_featured', true)->update(['is_featured' => false]);
        
        // Set new featured categories
        Category::whereIn('category_id', $categoryIds)->update(['is_featured' => true]);
    }

    /**
     * Apply filters to query
     */
    private function applyCategoryFilters($query, array $filters)
    {
        // Search filter
        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Has products filter
        if (isset($filters['has_products']) && $filters['has_products'] !== '') {
            $query->hasProducts($filters['has_products']);
        }

        // Parent filter
        if (isset($filters['parent_id']) && $filters['parent_id'] !== '') {
            $query->where('parent_id', $filters['parent_id']);
        }

        // Featured filter
        if (isset($filters['featured']) && $filters['featured']) {
            $query->featured();
        }

        // Level filter
        if (isset($filters['level'])) {
            switch ($filters['level']) {
                case 'top':
                    $query->topLevel();
                    break;
                case 'child':
                    $query->whereNotNull('parent_id');
                    break;
            }
        }
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query, array $filters)
    {
        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            switch ($filters['sort_by']) {
                case 'name':
                    $query->orderBy('category_name', $direction);
                    break;
                case 'products':
                    $query->orderBy('products_count', $direction);
                    break;
                case 'views':
                    // view_count is missing, fallback to category_name
                    $query->orderBy('category_name', $direction);
                    break;

                case 'date':
                    $query->orderBy('created_at', $direction);
                    break;
                case 'featured':
                    $query->orderBy('is_featured', 'desc')
                          ->orderBy('category_name', $direction);
                    break;
            }
        } else {
            $query->orderBy('category_name', 'asc');
        }
    }
}
