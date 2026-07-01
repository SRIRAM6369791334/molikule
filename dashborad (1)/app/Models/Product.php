<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected static function booted()
    {
        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = (new self)->generateUniqueSlug($product->name);
            }
        });

        static::saving(function ($product) {
            if ($product->stock_quantity < 0) {
                $product->stock_quantity = 0;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Resolve the route binding by slug OR numeric product_id.
     * This ensures edit/update works whether URL contains ID or slug.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where(function ($q) use ($value) {
            $q->where('slug', $value);
            if (is_numeric($value)) {
                $q->orWhere('product_id', (int) $value);
            }
        })
            ->first() ?? abort(404);
    }

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'image',
        'sku',
        'part_number',
        'barcode',
        'mrp_price',
        'compare_price',
        'cost_per_item',
        'original_price',
        'stock_quantity',
        'track_quantity',
        'continue_selling_when_out_of_stock',
        'low_stock_threshold',
        'active',
        'category_id',
        'brand_id',
        'slug',
        'gallery_images',
        'is_featured',
        'is_trending',
        'badge',
        'made_in',
        'warranty',
        'weight',
        'weight_unit',
        'length',
        'width',
        'height',
        'dimension',
        'dimension_unit',
        'condition',
        'supported_brands',
        'seller',
        'tags',
        'custom_fields',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'priority',
        'change_frequency',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'structured_data',
        'view_count',
    ];

    protected $casts = [
        'mrp_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'active' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'track_quantity' => 'boolean',
        'continue_selling_when_out_of_stock' => 'boolean',
        'low_stock_threshold' => 'integer',
        'gallery_images' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id')
            ->orderBy('mrp_price');
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id')
            ->where('active', true)
            ->orderBy('mrp_price');
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    /**
     * Scope for products with valid Stocks
     */
    public function scopeWithStock($query, $threshold = 15)
    {
        return $query->where('stock_quantity', '>', $threshold);
    }

    /**
     * Scope for products with low Stocks
     */
    public function scopeLowStock($query, $threshold = 15)
    {
        return $query->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', $threshold);
    }

    /**
     * Scope for products that are out of stock
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    /**
     * Scope for price range filtering
     */
    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min !== null)
            $query->where('mrp_price', '>=', $min);
        if ($max !== null)
            $query->where('mrp_price', '<=', $max);
        return $query;
    }

    /**
     * Scope for tag-based filtering (graceful handling if tags missing)
     */
    public function scopeByTags($query, array $tags)
    {
        if (!\Schema::hasColumn('products', 'tags'))
            return $query;

        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    /**
     * Enhanced advanced search
     */
    public function scopeAdvancedSearch($query, array $criteria)
    {
        if (isset($criteria['query'])) {
            $search = $criteria['query'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status === 'active')
            return $query->where('active', true);
        if ($status === 'inactive')
            return $query->where('active', false);
        return $query;
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        if ($categoryId)
            return $query->where('category_id', $categoryId);
        return $query;
    }

    public function scopeFilterByBrand($query, $brandId)
    {
        if ($brandId)
            return $query->where('brand_id', $brandId);
        return $query;
    }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format((float) $this->mrp_price, 2);
    }

    public function getImageUrlAttribute()
    {
        return $this->image;
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return asset('assets/images/product/img-1.png');
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('uploads/' . $value);
    }

    public function getBadgeClassAttribute()
    {
        return match ($this->badge) {
            'New' => 'discount-product',
            'Sale' => 'hot-product',
            'Popular' => 'popular-product',
            default => 'discount-product',
        };
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity > 10)
            return 'In Stock';
        if ($this->stock_quantity > 0)
            return 'Low Stock';
        return 'Out of Stock';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->active
            ? '<span class="badge bg-success rounded-pill px-3"><i class="bx bx-check-circle me-1"></i>Active</span>'
            : '<span class="badge bg-secondary rounded-pill px-3"><i class="bx bx-x-circle me-1"></i>Inactive</span>';
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('product_id', '!=', $this->product_id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        return $slug;
    }

    public function canBeDeleted(): bool
    {
        return true; // soft deletes used instead
    }
}
