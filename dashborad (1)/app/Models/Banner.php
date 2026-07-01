<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Traits\HasEnhancedFields;
use App\Traits\HasSlug;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'title', 'subtitle', 'minimage_url', 'image_url', 'position', 'is_active',
        'banner_type', 'starts_at', 'expires_at', 'show_on_hover', 'impression_count',
        'click_count', 'ctr', 'custom_data'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'show_on_hover' => 'boolean',
        'impression_count' => 'integer',
        'click_count' => 'integer',
        'ctr' => 'decimal:2',
        'custom_data' => 'json',
    ];

    const BANNER_TYPES = [
        'hero' => 'Hero Banner',
        'promotional' => 'Promotional',
        'category' => 'Category',
        'brand' => 'Brand',
        'product' => 'Product',
        'announcement' => 'Announcement'
    ];

    /**
     * Get the route key for the model
     */
    public function getRouteKey()
    {
        return $this->id;
    }

    /**
     * Enhanced relationships
     */
    public function getTarget()
    {
        if (!$this->target_type || !$this->target_id) {
            return null;
        }

        return match ($this->target_type) {
            'category' => Category::find($this->target_id),
            'brand' => Brand::find($this->target_id),
            'product' => Product::find($this->target_id),
            default => null
        };
    }

    /**
     * Enhanced scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('banner_type', $type);
    }

    public function scopeCurrent($query)
    {
        return $query->where(function($q) {
            $q->whereNull('starts_at')
              ->orWhere('starts_at', '<=', now());
        })->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }

    public function scopeOrdered($query)
    {
        return $query;
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('subtitle', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByStatus($query, string $status)
    {
        if ($status === 'active') {
            return $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            return $query->where('is_active', false);
        }

        return $query;
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)
                    ->current()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Enhanced accessors
     */
    public function getFormattedTitleAttribute()
    {
        return ucwords(strtolower($this->title));
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('uploads');
        return $disk->url(ltrim($value, '/'));
    }

    public function getMinimageUrlAttribute($value)
    {
        if (!$value) return null;
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('uploads');
        return $disk->url(ltrim($value, '/'));
    }

    public function getMiniImageUrlAttribute($value)
    {
        return $this->getMinimageUrlAttribute($value);
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary rounded-pill px-3"><i class="bx bx-pause-circle me-1"></i>Inactive</span>';
        }

        if (!$this->isCurrent()) {
            return '<span class="badge bg-warning rounded-pill px-3"><i class="bx bx-calendar me-1"></i>Scheduled</span>';
        }

        return '<span class="badge bg-success rounded-pill px-3"><i class="bx bx-check-circle me-1"></i>Active</span>';
    }

    public function getTypeBadgeAttribute()
    {
        $types = self::BANNER_TYPES;
        $type = $types[$this->banner_type] ?? 'Unknown';
        return '<span class="badge bg-info rounded-pill px-3">' . $type . '</span>';
    }

    public function getPerformanceBadgeAttribute()
    {
        if ($this->impression_count == 0) {
            return '<span class="badge bg-light text-dark rounded-pill px-3">No Data</span>';
        }

        $ctr = (float)$this->ctr;
        $class = $ctr > 2 ? 'bg-success' : ($ctr > 1 ? 'bg-warning' : 'bg-danger');
        $icon = $ctr > 2 ? 'trending-up' : ($ctr > 1 ? 'minus' : 'trending-down');
        
        return '<span class="badge ' . $class . ' rounded-pill px-3"><i class="bx bx-' . $icon . ' me-1"></i>' . $ctr . '% CTR</span>';
    }

    public function getCustomDataAttribute($value)
    {
        return $value ? (object) $value : collect();
    }

    /**
     * Status and scheduling helpers
     */
    public function isCurrent()
    {
        $now = now();
        
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        
        if ($this->expires_at && $this->expires_at < $now) {
            return false;
        }
        
        return true;
    }

    public function isScheduled()
    {
        return $this->starts_at && $this->starts_at > now();
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function getTimeStatusAttribute()
    {
        if ($this->isScheduled()) {
            return 'scheduled';
        }
        
        if ($this->isExpired()) {
            return 'expired';
        }
        
        if ($this->isCurrent()) {
            return 'active';
        }
        
        return 'inactive';
    }

    /**
     * Performance tracking
     */
    public function trackImpression()
    {
        $this->increment('impression_count');
    }

    public function trackClick()
    {
        $this->increment('click_count');
        $this->updateCtr();
    }

    public function updateCtr()
    {
        if ($this->impression_count > 0) {
            $ctr = round(($this->click_count / $this->impression_count) * 100, 2);
            $this->update(['ctr' => $ctr]);
        }
    }

    public function getFormattedCtrAttribute()
    {
        return $this->ctr ? number_format((float)$this->ctr, 2) . '%' : '0.00%';
    }

    public function getImpressionRateAttribute()
    {
        // This would be calculated based on total impressions vs page views
        // For now, return a placeholder
        return 'N/A';
    }

    /**
     * Image management
     */
    public function uploadImage($file, $type = 'image')
    {
        // Remove old image if exists
        $oldImage = $type === 'mini' ? $this->getRawOriginal('minimage_url') : $this->getRawOriginal('image_url');
        if ($oldImage) {
            $this->removeImage($type);
        }

        $path = $file->store("banners/{$type}", 'uploads');
        
        $column = $type === 'mini' ? 'minimage_url' : 'image_url';
        $this->update([$column => $path]);
        
        return $path;
    }

    public function removeImage($type = 'image')
    {
        $column = $type === 'mini' ? 'minimage_url' : 'image_url';
        $imagePath = $this->getRawOriginal($column);
        
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('uploads');
        
        if ($imagePath && $disk->exists($imagePath)) {
            $disk->delete($imagePath);
        }
        
        $this->update([$column => null]);
    }

    /**
     * Advanced operations
     */
    public function duplicate($newTitle = null)
    {
        $newTitle = $newTitle ?: $this->title . ' (Copy)';
        $newData = $this->toArray();
        
        unset($newData['id'], $newData['created_at'], $newData['updated_at'], $newData['impression_count'], $newData['click_count'], $newData['ctr']);
        $newData['title'] = $newTitle;
        
        return Banner::create($newData);
    }

    public function move($newPosition)
    {
        $this->update(['position' => $newPosition]);
    }

    public function reorder($newSortOrder)
    {
        $this->update(['sort_order' => $newSortOrder]);
    }

    /**
     * Cache management
     */
    public static function getCachedActiveBanners($position = null)
    {
        $cacheKey = $position ? "active_banners_{$position}" : 'active_banners';
        
        return Cache::remember($cacheKey, 300, function () use ($position) {
            $query = self::active()->current()->featured();
            
            if ($position) {
                $query->byPosition($position);
            }
            
            return $query->get();
        });
    }

    public static function getCachedBannersByType($type)
    {
        return Cache::remember("banners_by_type_{$type}", 300, function () use ($type) {
            return self::byType($type)->featured()->get();
        });
    }

    public function clearRelatedCaches()
    {
        $cacheKeys = [
            'active_banners',
            "active_banners_{$this->position}",
            "banners_by_type_{$this->banner_type}"
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Static helpers
     */
    public static function getBannerTypes()
    {
        return self::BANNER_TYPES;
    }

    public static function getActiveTypes()
    {
        return self::active()
                   ->current()
                   ->distinct()
                   ->pluck('banner_type')
                   ->mapWithKeys(function($type) {
                       return [$type => self::BANNER_TYPES[$type] ?? $type];
                   });
    }

    public static function getPositions()
    {
        return [
            'header' => 'Header',
            'hero' => 'Hero Section',
            'sidebar' => 'Sidebar',
            'footer' => 'Footer',
            'content' => 'Content',
            'popup' => 'Popup'
        ];
    }

    // Backward compatibility methods
    public function canBeDeleted(): bool
    {
        return true; // Banners don't have dependencies
    }

    /**
     * Additional properties expected by the view
     */
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->title));
    }

    public function getProductsCountBadgeAttribute()
    {
        return '<span class="badge bg-primary rounded-pill px-3">Banner</span>';
    }

    public function getProductsCountAttribute()
    {
        return 1; // Banners don't have products, but show as 1 for compatibility
    }
}
