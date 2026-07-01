<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'category_name',
        'slug',
        'parent_id',
        'image',
        'is_active',
        'is_featured',
        'show_on_homepage',
        'sort_order',
        'view_count',
        'product_count',
        'description',
        'short_description',
        'content',
        'category_content',
        'category_sidebar_content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'theme_primary_color',
        'theme_light_color',
        'theme_bg_image',
        'theme_border_radius',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id')
            ->where('active', true);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'category_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('category_name');
    }

    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->category_name));
    }

    public function getImageFullUrlAttribute()
    {
        $rawImage = $this->getRawOriginal('image');

        if (!$rawImage) {
            return asset('assets/images/placeholder-category.png');
        }

        return productImageUrl($rawImage);
    }

    public function getShopUrlAttribute()
    {
        return route('shop', ['category' => $this->slug ?? $this->category_id]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('products', function ($productQuery) {
            $productQuery->where('active', true);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('category_name');
    }

    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true)->where('is_active', true);
    }
}
