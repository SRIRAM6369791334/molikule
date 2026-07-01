<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\BannerService;
use App\Services\AnalyticsService;
use App\Services\CacheService;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class BannerApiController extends Controller
{
    private $bannerService;
    private $analyticsService;
    private $cacheService;

    public function __construct(
        BannerService $bannerService,
        AnalyticsService $analyticsService,
        CacheService $cacheService
    ) {
        $this->bannerService = $bannerService;
        $this->analyticsService = $analyticsService;
        $this->cacheService = $cacheService;
    }

    /**
     * Get all banners with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->all();
            $perPage = $request->get('per_page', 15);
            
            $banners = $this->bannerService->getFilteredBanners($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $banners->items(),
                'meta' => [
                    'current_page' => $banners->currentPage(),
                    'last_page' => $banners->lastPage(),
                    'per_page' => $banners->perPage(),
                    'total' => $banners->total(),
                    'from' => $banners->firstItem(),
                    'to' => $banners->lastItem()
                ],
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single banner
     */
    public function show(Banner $banner): JsonResponse
    {
        try {
            $banner->load(['analytics']);
            
            return response()->json([
                'success' => true,
                'data' => $banner
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store new banner
     */
    public function store(StoreBannerRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
            
            // Handle image upload if present
            if ($request->hasFile('image')) {
                $validated['image_url'] = $request->file('image')->store('banners', 'uploads');
            }
            
            $banner = Banner::create($validated);
            
            // Clear related caches
            $this->cacheService->clearBannerCaches($banner->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner created successfully',
                'data' => $banner
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update banner
     */
    public function update(UpdateBannerRequest $request, Banner $banner): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->image_url && \Storage::disk('uploads')->exists($banner->image_url)) {
                    \Storage::disk('uploads')->delete($banner->image_url);
                }
                $validated['image_url'] = $request->file('image')->store('banners', 'uploads');
            }
            
            $banner->update($validated);
            
            // Clear related caches
            $this->cacheService->clearBannerCaches($banner->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner updated successfully',
                'data' => $banner
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete banner
     */
    public function destroy(Banner $banner): JsonResponse
    {
        try {
            // Delete banner image if exists
            if ($banner->image_url && \Storage::disk('uploads')->exists($banner->image_url)) {
                \Storage::disk('uploads')->delete($banner->image_url);
            }
            
            $banner->delete();
            
            // Clear related caches
            $this->cacheService->clearBannerCaches($banner->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active banners (for frontend)
     */
    public function active(Request $request): JsonResponse
    {
        try {
            $position = $request->get('position');
            $now = Carbon::now();
            
            $query = Banner::where('is_active', true)
                ->where(function($q) use ($now) {
                    $q->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', $now);
                })
                ->where(function($q) use ($now) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', $now);
                })
                ->ordered();
            
            if ($position) {
                $query->where('position', $position);
            }
            
            $banners = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $banners
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching active banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get banner statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->bannerService->getBannersWithStats();
            
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
     * Track banner impression
     */
    public function trackImpression(Banner $banner, Request $request): JsonResponse
    {
        try {
            $this->analyticsService->trackBannerImpression($banner->id, $request->ip());
            
            return response()->json([
                'success' => true,
                'message' => 'Impression tracked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error tracking impression',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track banner click
     */
    public function trackClick(Banner $banner, Request $request): JsonResponse
    {
        try {
            $this->analyticsService->trackBannerClick($banner->id, $request->ip());
            
            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error tracking click',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus(Banner $banner): JsonResponse
    {
        try {
            $banner->update(['is_active' => !$banner->is_active]);
            
            // Clear related caches
            $this->cacheService->clearBannerCaches($banner->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner status updated',
                'data' => [
                    'is_active' => $banner->is_active,
                    'status' => $banner->is_active ? 'active' : 'inactive'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating banner status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get banner analytics
     */
    public function analytics(Banner $banner, Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $analytics = $this->analyticsService->getBannerAnalytics($banner->id, $days);
            
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
                'ids.*' => 'required|integer|exists:banners,id',
                'action' => 'required|in:activate,deactivate,delete'
            ]);
            
            $action = $validated['action'];
            $ids = $validated['ids'];

            // Additional validation: verify all banners exist before processing
            $existingCount = Banner::whereIn('id', $ids)->count();
            if ($existingCount !== count($ids)) {
                $missingIds = array_diff($ids, Banner::whereIn('id', $ids)->pluck('id')->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Some banners not found',
                    'missing_ids' => array_values($missingIds),
                    'error_code' => 'BANNERS_NOT_FOUND'
                ], 404);
            }
            
            $affected = 0;
            switch ($action) {
                case 'activate':
                    $affected = Banner::whereIn('id', $ids)->update(['is_active' => 1]);
                    break;
                case 'deactivate':
                    $affected = Banner::whereIn('id', $ids)->update(['is_active' => 0]);
                    break;
                case 'delete':
                    try {
                        $banners = Banner::whereIn('id', $ids)->get();
                        foreach ($banners as $banner) {
                            if ($banner->image_url && \Storage::disk('uploads')->exists($banner->image_url)) {
                                \Storage::disk('uploads')->delete($banner->image_url);
                            }
                        }
                        $affected = Banner::whereIn('id', $ids)->delete();
                    } catch (\Exception $deleteError) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error deleting banners',
                            'error' => config('app.debug') ? $deleteError->getMessage() : 'An error occurred',
                            'error_code' => 'DELETE_FAILED'
                        ], 500);
                    }
                    break;
            }
            
            // Clear caches for affected banners
            $this->cacheService->clearBannerCaches();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully {$action}d {$affected} banner(s)",
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

    /**
     * Get banners by position
     */
    public function byPosition(Request $request): JsonResponse
    {
        try {
            $position = $request->get('position');
            $limit = $request->get('limit', 5);
            
            if (!$position) {
                return response()->json([
                    'success' => false,
                    'message' => 'Position parameter required'
                ], 400);
            }
            
            $now = Carbon::now();
            $banners = Banner::where('is_active', true)
                ->where('position', $position)
                ->where(function($q) use ($now) {
                    $q->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', $now);
                })
                ->where(function($q) use ($now) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', $now);
                })
                ->ordered()
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $banners
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching banners by position',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}