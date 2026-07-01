<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerService
{
    public function getTotalBanners()
    {
        return Banner::count();
    }

    public function getTotalActiveBanners()
    {
        return Banner::where('is_active', true)->count();
    }

    public function updateBannerStatus($id, $status)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = $status;
        $banner->save();
        return $banner;
    }

    public function getFilteredBanners(array $filters, $perPage = 16)
    {
        $query = Banner::query();

        // Search filter
        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort order
        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            switch($filters['sort_by']) {
                case 'name':
                    $query->orderBy('title', $direction);
                    break;
                case 'date':
                    $query->orderBy('created_at', $direction);
                    break;
                default:
                    $query->orderBy('title', 'asc');
            }
        } else {
            $query;
        }

        return $query->paginate($perPage);
    }

    public function getBannersWithStats()
    {
        // Get comprehensive banner statistics
        $total = Banner::count();
        $active = Banner::where('is_active', true)->count();
        $inactive = Banner::where('is_active', false)->count();

        return [
            'total_banners' => $total,
            'active_banners' => $active,
            'inactive_banners' => $inactive,
        ];
    }

    public function bulkUpdateBanners(array $bannerIds, array $updateData)
    {
        $banners = Banner::whereIn('id', $bannerIds)->get();

        $updated = 0;
        foreach ($banners as $banner) {
            $banner->update($updateData);
            $updated++;
        }

        return $updated;
    }

    public function bulkActivateBanners(array $bannerIds)
    {
        return $this->bulkUpdateBanners($bannerIds, ['is_active' => true]);
    }

    public function bulkDeactivateBanners(array $bannerIds)
    {
        return $this->bulkUpdateBanners($bannerIds, ['is_active' => false]);
    }

    public function bulkDeleteBanners(array $bannerIds)
    {
        $banners = Banner::whereIn('id', $bannerIds)->get();

        $deleted = 0;

        foreach ($banners as $banner) {
            // Delete banner image if exists
            if ($banner->image_url && \Storage::disk('public')->exists($banner->image_url)) {
                \Storage::disk('public')->delete($banner->image_url);
            }

            $banner->delete();
            $deleted++;
        }

        return ['deleted' => $deleted, 'skipped' => 0];
    }

    public function searchBanners(string $query, int $limit = 10)
    {
        return Banner::where('title', 'like', "%{$query}%")
                   ->orWhere('description', 'like', "%{$query}%")
                   ->active()
                   ->take($limit)
                   ->get(['id', 'title', 'description']);
    }

    /**
     * Get banners for dropdown/filtering
     */
    public function getBannersForSelect($includeInactive = false)
    {
        $query = Banner::orderBy('title');

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->get(['id', 'title']);
    }

    /**
     * Get banners by position/type
     */
    public function getBannersByPosition($position, $limit = null)
    {
        $query = Banner::where('position', $position)
                      ->where('is_active', 1)->orderBy('sort_order')->orderBy('title');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get scheduled banners
     */
    public function getScheduledBanners()
    {
        $now = now();
        return Banner::where('is_active', 1)
                   ->where(function($query) use ($now) {
                       $query->whereNull('start_date')
                             ->orWhere('start_date', '<=', $now);
                   })
                   ->where(function($query) use ($now) {
                       $query->whereNull('end_date')
                             ->orWhere('end_date', '>=', $now);
                   })
                   ->get();
    }

    /**
     * Get banner performance analytics
     */
    public function getBannerPerformance($bannerId, $days = 30)
    {
        $banner = Banner::find($bannerId);
        if (!$banner) {
            return null;
        }

        $impressions = $banner->impression_count ?? 0;
        $clicks = $banner->click_count ?? 0;
        $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

        return [
            'banner_id' => $bannerId,
            'title' => $banner->title,
            'impressions' => $impressions,
            'clicks' => $clicks,
            'ctr' => $ctr,
            'position' => $banner->position,
            'is_active' => $banner->is_active,
            'start_date' => $banner->start_date,
            'end_date' => $banner->end_date,
            'last_updated' => now()
        ];
    }

    /**
     * Track banner impression
     */
    public function trackImpression($bannerId)
    {
        $banner = Banner::find($bannerId);
        if ($banner) {
            $banner->increment('impression_count');
        }
    }

    /**
     * Track banner click
     */
    public function trackClick($bannerId, $targetUrl = null)
    {
        $banner = Banner::find($bannerId);
        if ($banner) {
            $banner->increment('click_count');
            
            // Log click event if target URL provided
            if ($targetUrl) {
                \Log::info('Banner click tracked', [
                    'banner_id' => $bannerId,
                    'target_url' => $targetUrl,
                    'timestamp' => now(),
                    'ip_address' => request()->ip()
                ]);
            }
        }
    }

    /**
     * Get banner analytics dashboard
     */
    public function getBannerAnalytics()
    {
        $banners = Banner::all();
        
        $totalImpressions = $banners->sum('impression_count');
        $totalClicks = $banners->sum('click_count');
        $overallCtr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;
        
        return [
            'total_banners' => $banners->count(),
            'active_banners' => $banners->where('is_active', 1)->count(),
            'total_impressions' => $totalImpressions,
            'total_clicks' => $totalClicks,
            'overall_ctr' => $overallCtr,
            'top_performing_banners' => $this->getTopPerformingBanners(5),
            'positions_summary' => $this->getBannersByPositionSummary()
        ];
    }

    /**
     * Get top performing banners
     */
    public function getTopPerformingBanners($limit = 10)
    {
        return Banner::where('is_active', 1)
                   ->where('impression_count', '>', 0)
                   ->selectRaw('*, (click_count / impression_count * 100) as ctr')
                   ->orderByRaw('ctr DESC')
                   ->orderBy('impression_count', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get banners summary by position
     */
    private function getBannersByPositionSummary()
    {
        $positions = Banner::where('is_active', 1)
                         ->selectRaw('position, COUNT(*) as count, SUM(impression_count) as total_impressions, SUM(click_count) as total_clicks')
                         ->groupBy('position')
                         ->get();

        return $positions->map(function($position) {
            $ctr = $position->total_impressions > 0 ? round(($position->total_clicks / $position->total_impressions) * 100, 2) : 0;
            return [
                'position' => $position->position,
                'count' => $position->count,
                'total_impressions' => $position->total_impressions,
                'total_clicks' => $position->total_clicks,
                'ctr' => $ctr
            ];
        });
    }

    /**
     * Clear banner caches
     */
    public function clearBannerCaches()
    {
        $cacheKeys = [
            'banners_total_count',
            'banners_active_count',
            'banners_stats',
            'scheduled_banners',
            'banner_analytics',
            'top_performing_banners'
        ];

        foreach ($cacheKeys as $key) {
            \Illuminate\Support\Facades\Cache::forget($key);
        }
    }

    /**
     * Update banner schedule
     */
    public function updateBannerSchedule($bannerId, $startDate = null, $endDate = null)
    {
        $banner = Banner::findOrFail($bannerId);
        $banner->start_date = $startDate;
        $banner->end_date = $endDate;
        $banner->save();

        $this->clearBannerCaches();
        return $banner;
    }

    /**
     * Get expired banners
     */
    public function getExpiredBanners()
    {
        return Banner::where('is_active', 1)
                   ->whereNotNull('end_date')
                   ->where('end_date', '<', now())
                   ->get();
    }

    /**
     * Deactivate expired banners
     */
    public function deactivateExpiredBanners()
    {
        $expiredBanners = $this->getExpiredBanners();
        $count = 0;

        foreach ($expiredBanners as $banner) {
            $banner->is_active = false;
            $banner->save();
            $count++;
        }

        if ($count > 0) {
            $this->clearBannerCaches();
        }

        return $count;
    }
}
