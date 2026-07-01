<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'image',
        'sku',
        'mrp_price',
        'compare_price',
        'stock_quantity',
        'active',
        'category_id',
        'brand_id',
        'slug',
        'gallery_images',
        'is_featured',
        'is_trending',
        'badge',
        'low_stock_threshold',
    ];

    protected $casts = [
        'mrp_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'active' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'gallery_images' => 'json',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->firstOrFail();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id')
            ->where('active', true)
            ->orderBy('mrp_price');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'product_id');
    }

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

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeSearch($query, string $term)
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function ($productQuery) use ($term) {
            $like = '%' . $term . '%';

            $productQuery->where('name', 'like', $like)
                ->orWhere('sku', 'like', $like)
                ->orWhere('short_description', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhereHas('category', function ($categoryQuery) use ($like) {
                    $categoryQuery->where('category_name', 'like', $like);
                })
                ->orWhereHas('brand', function ($brandQuery) use ($like) {
                    $brandQuery->where('brand_name', 'like', $like);
                });
        });
    }

    public function getImageFullUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/shop/shop-15.png');
        }

        $rawImage = $this->getRawOriginal('image');
        return productImageUrl($rawImage);
    }

    public function getFormattedPriceAttribute()
    {
        return formatPrice($this->mrp_price);
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->mrp_price) {
            return round((($this->compare_price - $this->mrp_price) / $this->compare_price) * 100);
        }

        return 0;
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

    public function getCategoryFilterSlugAttribute()
    {
        return Str::slug($this->category->category_name ?? 'all');
    }

    public function getStockLabelAttribute()
    {
        if ($this->stock_quantity > 10) {
            return 'In Stock';
        }

        if ($this->stock_quantity > 0) {
            return 'Low Stock';
        }

        return 'Out of Stock';
    }
}
