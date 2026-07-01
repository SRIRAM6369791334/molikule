<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\AnalyticsService;
use App\Services\CacheService;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    private $categoryService;
    private $analyticsService;
    private $cacheService;

    public function __construct(
        CategoryService $categoryService,
        AnalyticsService $analyticsService,
        CacheService $cacheService
    ) {
        $this->categoryService = $categoryService;
        $this->analyticsService = $analyticsService;
        $this->cacheService = $cacheService;
    }

    /**
     * Get all categories with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 15);
            
            $categories = $this->categoryService->getFilteredCategories($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $categories->items(),
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'from' => $categories->firstItem(),
                    'to' => $categories->lastItem()
                ],
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single category
     */
    public function show(Category $category): JsonResponse
    {
        try {
            // Track category view
            $this->analyticsService->trackCategoryView($category->category_id, auth()->id());
            
            $category->load(['products', 'children', 'parent']);
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store new category
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
            
            $category = Category::create($validated);
            
            // Clear related caches
            $this->cacheService->clearCategoryCaches($category->category_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category->load(['parent', 'children'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update category
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());
            
            // Clear related caches
            $this->cacheService->clearCategoryCaches($category->category_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category->load(['parent', 'children'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete category
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            // Check for products dependency
            if ($category->products()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing products'
                ], 422);
            }
            
            // Check for child categories
            if ($category->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with child categories'
                ], 422);
            }
            
            $category->delete();
            
            // Clear related caches
            $this->cacheService->clearCategoryCaches($category->category_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search categories
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $limit = $request->get('limit', 10);
            
            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query required'
                ], 400);
            }
            
            $results = $this->categoryService->searchCategories($query, $limit);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->categoryService->getCategoriesWithStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category tree
     */
    public function tree(): JsonResponse
    {
        try {
            $tree = Category::with('children.children')
                ->whereNull('parent_id')
                ->active()
                ->ordered()
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $tree
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching category tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category): JsonResponse
    {
        try {
            $category->update(['is_active' => !$category->is_active]);
            
            // Clear related caches
            $this->cacheService->clearCategoryCaches($category->category_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Category status updated',
                'data' => [
                    'is_active' => $category->is_active,
                    'status' => $category->is_active ? 'active' : 'inactive'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category analytics
     */
    public function analytics(Category $category, Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $analytics = $this->analyticsService->getCategoryAnalytics($category->category_id, $days);
            
            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkOperations(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:categories,category_id',
                'action' => 'required|in:activate,deactivate,delete'
            ]);
            
            $action = $validated['action'];
            $ids = $validated['ids'];

            // Additional validation: verify all categories exist before processing
            $existingCount = Category::whereIn('category_id', $ids)->count();
            if ($existingCount !== count($ids)) {
                $missingIds = array_diff($ids, Category::whereIn('category_id', $ids)->pluck('category_id')->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Some categories not found',
                    'missing_ids' => array_values($missingIds),
                    'error_code' => 'CATEGORIES_NOT_FOUND'
                ], 404);
            }
            
            $affected = 0;
            switch ($action) {
                case 'activate':
                    $affected = Category::whereIn('category_id', $ids)->update(['is_active' => 1]);
                    break;
                case 'deactivate':
                    $affected = Category::whereIn('category_id', $ids)->update(['is_active' => 0]);
                    break;
                case 'delete':
                    $affected = Category::whereIn('category_id', $ids)->delete();
                    break;
            }
            
            // Clear caches for affected categories
            $this->cacheService->clearCategoryCaches();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully {$action}d {$affected} category(s)",
                'affected_count' => $affected
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'error_code' => 'BULK_OPERATION_FAILED'
            ], 500);
        }
    }
}