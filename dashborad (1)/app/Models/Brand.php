<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $primaryKey = 'brand_id';

    protected $fillable = [
        'brand_name',
        'logo',
        'slug',
        'is_active',
        'is_featured',
        'show_on_homepage',
        'sort_order',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'is_featured'      => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order'       => 'integer',
    ];

    /**
     * Get the route key for the model
     */
    public function getRouteKey()
    {
        return $this->slug ?? $this->brand_id;
    }

    public function getRouteKeyName()
    {
        return 'brand_id';
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'brand_id')
                    ->where('active', 1)
                    ->withStock();
    }

    public function featuredProducts()
    {
        return $this->hasMany(Product::class, 'brand_id')
                    ->featured()
                    ->limit(8);
    }

    /**
     * SEO enhancements
     */
    public function getSeoTitleAttribute()
    {
        return $this->meta_title ?: $this->brand_name . ' | Brands';
    }

    public function getSeoDescriptionAttribute()
    {
        $description = $this->attributes['description'] ?? $this->brand_name;

        return $this->meta_description ?: Str::limit($description, 160);
    }

    public function getSeoUrlAttribute()
    {
        return route('brands.show', $this->slug ?: $this->brand_id);
    }

    /**
     * Enhanced accessors
     */
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->brand_name));
    }

    public function getLogoAttribute($value)
    {
        if (!$value) return null;
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('uploads/' . $value);

    }

    public function getLogoUrlAttribute()
    {
        return $this->logo;
    }

    public function setLogoUrlAttribute($value)
    {
        $this->attributes['logo'] = $value;
    }

    public function getSocialLinksAttribute($value)
    {
        return $value ? (object) $value : collect();
    }

    public function getBrandColorsAttribute($value)
    {
        return $value ? (object) $value : collect();
    }

    public function getPrimaryColorAttribute()
    {
        return $this->brand_colors['primary'] ?? '#000000';
    }

    public function getSecondaryColorAttribute()
    {
        return $this->brand_colors['secondary'] ?? '#ffffff';
    }

    /**
     * Enhanced scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithProducts($query)
    {
        return $query->whereHas('products');
    }

    public function scopeOrdered($query)
    {
        return $query;
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('brand_name', 'like', "%{$search}%");
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

    public function scopeFilterByProductCount($query, $min = 0, $max = null)
    {
        $query->where('product_count', '>=', $min);
        
        if ($max !== null) {
            $query->where('product_count', '<=', $max);
        }
        
        return $query;
    }

    /**
     * Product count management
     */
    public function updateProductCount()
    {
        $count = $this->products()->count();
        $this->update(['product_count' => $count]);
        return $count;
    }

    public function getCachedProductCountAttribute()
    {
        return Cache::remember("brand_product_count_{$this->brand_id}", 1800, function () {
            return $this->product_count ?: $this->products()->count();
        });
    }

    /**
     * Logo management
     */
    public function uploadLogo($file)
    {
        // Remove old logo if exists
        if ($this->logo) {
            $this->removeLogo();
        }

        $path = $file->store('brands/logos', 'uploads');
        $this->update(['logo' => $path]);
        
        return $path;
    }

    public function removeLogo()
    {
        $logoPath = $this->getRawOriginal('logo');
        
        if ($logoPath && \Storage::disk('uploads')->exists($logoPath)) {
            \Storage::disk('uploads')->delete($logoPath);
        }
        
        $this->update(['logo' => null]);
    }

    /**
     * Social media helpers
     */
    public function getSocialLink($platform)
    {
        return $this->social_links[$platform] ?? null;
    }

    public function setSocialLink($platform, $url)
    {
        $socialLinks = $this->social_links;
        $socialLinks[$platform] = $url;
        $this->update(['social_links' => $socialLinks]);
    }

    public function hasSocialMedia()
    {
        return $this->social_links->isNotEmpty();
    }

    public function getSocialPlatforms()
    {
        return $this->social_links->keys()->toArray();
    }

    /**
     * Advanced brand operations
     */
    public function canBeDeleted(): bool
    {
        return $this->products()->count() === 0;
    }

    public function duplicate($newName = null)
    {
        $newName = $newName ?: $this->brand_name . ' (Copy)';
        $newData = $this->toArray();
        
        unset($newData['brand_id'], $newData['created_at'], $newData['updated_at']);
        $newData['brand_name'] = $newName;
        $newData['slug'] = $this->generateUniqueSlug($newName);
        $newData['logo'] = null; // Don't duplicate logo
        
        return Brand::create($newData);
    }

    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Brand::where('slug', $slug)->where('brand_id', '!=', $this->brand_id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
        return $this->view_count;
    }

    /**
     * Cache management
     */
    public function clearRelatedCaches()
    {
        $cacheKeys = [
            "brand_product_count_{$this->brand_id}",
            "brand_cache_{$this->brand_id}",
            "featured_brands",
            "brand_tree"
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    public static function getFeaturedBrands()
    {
        return Cache::remember('featured_brands', 1800, function () {
            return self::active()
                      ->featured()
                      ->ordered()
                      ->get();
        });
    }

    public static function getBrandsWithProductCounts()
    {
        return Cache::remember('brands_with_counts', 1800, function () {
            return self::withCount('products')
                      ->having('products_count', '>', 0)
                      ->ordered()
                      ->get();
        });
    }

    // Backward compatibility methods
    public function getProductsCountAttribute()
    {
        // First, check if products_count attribute exists (from withCount query)
        // Use array_key_exists to avoid recursive accessor calls
        if (array_key_exists('products_count', $this->attributes) && $this->attributes['products_count'] !== null) {
            return $this->attributes['products_count'];
        }
        
        // If product_count column exists in database, use it
        if (array_key_exists('product_count', $this->attributes) && $this->attributes['product_count'] !== null) {
            return $this->attributes['product_count'];
        }
        
        // Otherwise, calculate from the relationship
        return $this->products()->count();
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            return '<span class="badge bg-success rounded-pill px-3"><i class="bx bx-check-circle me-1"></i>Active</span>';
        } else {
            return '<span class="badge bg-secondary rounded-pill px-3"><i class="bx bx-x-circle me-1"></i>Inactive</span>';
        }
    }

    public function getProductsCountBadgeAttribute()
    {
        $count = $this->products_count;
        if ($count === 0) {
            return '<span class="badge bg-light text-dark rounded-pill px-3">No Products</span>';
        } elseif ($count < 5) {
            return '<span class="badge bg-warning rounded-pill px-3">' . $count . ' Products</span>';
        } else {
            return '<span class="badge bg-primary rounded-pill px-3">' . $count . ' Products</span>';
        }
    }

    // Original scopes for backward compatibility
    public function scopeHasProducts($query, string $hasProducts)
    {
        if ($hasProducts === 'with_products') {
            return $query->whereHas('products');
        } elseif ($hasProducts === 'without_products') {
            return $query->whereDoesntHave('products');
        }

        return $query;
    }
}
