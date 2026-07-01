<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\BrandService;
use App\Services\AnalyticsService;
use App\Services\CacheService;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrandApiController extends Controller
{
    private $brandService;
    private $analyticsService;
    private $cacheService;

    public function __construct(
        BrandService $brandService,
        AnalyticsService $analyticsService,
        CacheService $cacheService
    ) {
        $this->brandService = $brandService;
        $this->analyticsService = $analyticsService;
        $this->cacheService = $cacheService;
    }

    /**
     * Get all brands with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 15);
            
            $brands = $this->brandService->getFilteredBrands($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $brands->items(),
                'meta' => [
                    'current_page' => $brands->currentPage(),
                    'last_page' => $brands->lastPage(),
                    'per_page' => $brands->perPage(),
                    'total' => $brands->total(),
                    'from' => $brands->firstItem(),
                    'to' => $brands->lastItem()
                ],
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single brand
     */
    public function show(Brand $brand): JsonResponse
    {
        try {
            // Track brand view
            $this->analyticsService->trackBrandView($brand->brand_id, auth()->id());
            
            $brand->load(['products', 'products.category']);
            
            return response()->json([
                'success' => true,
                'data' => $brand
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store new brand
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
            
            $brand = Brand::create($validated);
            
            // Clear related caches
            $this->cacheService->clearBrandCaches($brand->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully',
                'data' => $brand
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update brand
     */
    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        try {
            $brand->update($request->validated());
            
            // Clear related caches
            $this->cacheService->clearBrandCaches($brand->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully',
                'data' => $brand
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete brand
     */
    public function destroy(Brand $brand): JsonResponse
    {
        try {
            // Check for products dependency
            if ($brand->products()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete brand with existing products'
                ], 422);
            }
            
            $brand->delete();
            
            // Clear related caches
            $this->cacheService->clearBrandCaches($brand->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search brands
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
            
            $results = $this->brandService->searchBrands($query, $limit);
            
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
     * Get brand statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->brandService->getBrandsWithStats();
            
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
     * Get featured brands
     */
    public function featured(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $brands = Brand::active()
                ->withCount('products')
                ->having('products_count', '>', 0)
                ->orderBy('products_count', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $brands
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching featured brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle brand status
     */
    public function toggleStatus(Brand $brand): JsonResponse
    {
        try {
            $brand->update(['is_active' => !$brand->is_active]);
            
            // Clear related caches
            $this->cacheService->clearBrandCaches($brand->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Brand status updated',
                'data' => [
                    'is_active' => $brand->is_active,
                    'status' => $brand->is_active ? 'active' : 'inactive'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating brand status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get brand analytics
     */
    public function analytics(Brand $brand, Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $analytics = $this->analyticsService->getBrandAnalytics($brand->brand_id, $days);
            
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
                'ids.*' => 'required|integer|exists:brands,brand_id',
                'action' => 'required|in:activate,deactivate,delete'
            ]);
            
            $action = $validated['action'];
            $ids = $validated['ids'];

            // Additional validation: verify all brands exist before processing
            $existingCount = Brand::whereIn('brand_id', $ids)->count();
            if ($existingCount !== count($ids)) {
                $missingIds = array_diff($ids, Brand::whereIn('brand_id', $ids)->pluck('brand_id')->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Some brands not found',
                    'missing_ids' => array_values($missingIds),
                    'error_code' => 'BRANDS_NOT_FOUND'
                ], 404);
            }
            
            $affected = 0;
            switch ($action) {
                case 'activate':
                    $affected = Brand::whereIn('brand_id', $ids)->update(['is_active' => 1]);
                    break;
                case 'deactivate':
                    $affected = Brand::whereIn('brand_id', $ids)->update(['is_active' => 0]);
                    break;
                case 'delete':
                    $affected = Brand::whereIn('brand_id', $ids)->delete();
                    break;
            }
            
            // Clear caches for affected brands
            $this->cacheService->clearBrandCaches();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully {$action}d {$affected} brand(s)",
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