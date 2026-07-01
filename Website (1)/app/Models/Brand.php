<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $table = 'brands';
    protected $primaryKey = 'brand_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'brand_name',
        'slug',
        'logo',
        'brand_type',
        'is_active',
        'is_featured',
        'show_on_homepage',
        'is_verified',
        'is_premium',
        'product_count',
        'view_count',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'brand_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'brand_id', 'brand_id')
            ->where('active', true);
    }

    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->brand_name));
    }

    public function getLogoFullUrlAttribute()
    {
        $rawLogo = $this->getRawOriginal('logo');

        if (!$rawLogo) {
            return asset('assets/images/placeholder-brand.png');
        }

        return productImageUrl($rawLogo);
    }

    public function getShopUrlAttribute()
    {
        return route('shop', ['brand' => $this->slug ?: $this->brand_id]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true)->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('brand_name');
    }
}
