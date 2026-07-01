<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\AnalyticsService;
use App\Services\CacheService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    private $productService;
    private $analyticsService;
    private $cacheService;

    public function __construct(
        ProductService $productService,
        AnalyticsService $analyticsService,
        CacheService $cacheService
    ) {
        $this->productService = $productService;
        $this->analyticsService = $analyticsService;
        $this->cacheService = $cacheService;
    }

    /**
     * Get all products with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 15);
            
            $products = $this->productService->getFilteredProducts($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem()
                ],
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single product
     */
    public function show(Product $product): JsonResponse
    {
        try {
            // Track product view
            $this->analyticsService->trackProductView($product->product_id, auth()->id());
            
            $product->load(['category', 'brand', 'variants']);
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store new product
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['active'] = $request->has('active') ? $request->boolean('active') : true;
            
            $product = Product::create($validated);
            
            // Clear related caches
            $this->cacheService->clearProductCaches($product->product_id, $product->category_id, $product->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load(['category', 'brand'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $product->update($request->validated());
            
            // Clear related caches
            $this->cacheService->clearProductCaches($product->product_id, $product->category_id, $product->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load(['category', 'brand'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            // Check if product actually exists in database
            if (!$product->exists || !$product->product_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found - it may have already been deleted'
                ], 404);
            }

            $productId = $product->product_id;
            $categoryId = $product->category_id;
            $brandId = $product->brand_id;

            // Check for orders dependency
            $hasOrders = \App\Models\OrderItem::where(function($query) use ($product) {
                $query->where('itemable_type', Product::class)
                      ->where('itemable_id', $product->product_id);
            })->exists();
            
            if ($hasOrders) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete product with existing orders'
                ], 422);
            }
            
            $product->delete();
            
            // ✅ CRITICAL: Clear caches AFTER deletion to prevent 404 errors on re-delete
            $this->cacheService->clearProductCaches($productId, $categoryId, $brandId);
            
            // Also clear related lists and stats
            \Illuminate\Support\Facades\Cache::forget('product_filter_stats');
            \Illuminate\Support\Facades\Cache::forget('products_total_count');
            \Illuminate\Support\Facades\Cache::forget('products_active_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Product not found - likely already deleted
            return response()->json([
                'success' => false,
                'message' => 'Product not found - it may have already been deleted'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Product API deletion failed', [
                'error' => $e->getMessage(),
                'product_id' => $product->product_id ?? 'unknown',
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $options = $request->all();
            
            if (empty($query) && empty($options)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query or filters required'
                ], 400);
            }
            
            $results = $this->productService->searchProducts($query, $options);
            
            return response()->json([
                'success' => true,
                'data' => $results->items(),
                'meta' => [
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total()
                ]
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
     * Get product statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->productService->getProductStats();
            
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
     * Toggle product status
     */
    public function toggleStatus(Product $product): JsonResponse
    {
        try {
            $product->update(['active' => !$product->active]);
            
            // Clear related caches
            $this->cacheService->clearProductCaches($product->product_id, $product->category_id, $product->brand_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Product status updated',
                'data' => [
                    'active' => $product->active,
                    'status' => $product->active ? 'active' : 'inactive'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product analytics
     */
    public function analytics(Product $product, Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $analytics = $this->analyticsService->getProductAnalytics($product->product_id, $days);
            
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
                'ids.*' => 'required|integer|exists:products,product_id',
                'action' => 'required|in:activate,deactivate,delete'
            ]);
            
            $action = $validated['action'];
            $ids = $validated['ids'];

            // Additional validation: verify all products exist before processing
            $existingCount = Product::whereIn('product_id', $ids)->count();
            if ($existingCount !== count($ids)) {
                $missingIds = array_diff($ids, Product::whereIn('product_id', $ids)->pluck('product_id')->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Some products not found',
                    'missing_ids' => array_values($missingIds),
                    'error_code' => 'PRODUCTS_NOT_FOUND'
                ], 404);
            }
            
            $affected = 0;
            switch ($action) {
                case 'activate':
                    $affected = Product::whereIn('product_id', $ids)->update(['active' => 1]);
                    break;
                case 'deactivate':
                    $affected = Product::whereIn('product_id', $ids)->update(['active' => 0]);
                    break;
                case 'delete':
                    $affected = Product::whereIn('product_id', $ids)->delete();
                    break;
            }
            
            // Clear caches for affected products
            $this->cacheService->clearProductCaches();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully {$action}d {$affected} product(s)",
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