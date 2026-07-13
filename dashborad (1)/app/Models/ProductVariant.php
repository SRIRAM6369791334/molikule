<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected static function booted()
    {
        static::saving(function ($variant) {
            if ($variant->stock_quantity < 0) {
                $variant->stock_quantity = 0;
            }
        });
    }

    protected $fillable = [
        'product_id', 'variant_name', 'variant_type', 'value', 'variant_unit',
        'mrp_price', 'compare_price', 'stock_quantity', 'low_stock', 'sku',
        'variant_image', 'gallery_images', 'active', 'is_featured', 'is_trending', 'discount_type', 'discount_value',
    ];

    protected $appends = ['main_image', 'discounted_price', 'gallery_urls'];


    protected $casts = [
        'mrp_price'      => 'decimal:2',
        'compare_price'  => 'decimal:2',
        'stock_quantity' => 'integer',
        'active'         => 'boolean',
        'is_featured'    => 'boolean',
        'is_trending'    => 'boolean',
        'discount_value' => 'decimal:2',
        'gallery_images' => 'json',
    ];

    public function getGalleryUrlsAttribute()
    {
        if (empty($this->gallery_images) || !is_array($this->gallery_images)) {
            return [];
        }

        return array_values(array_filter(array_map(function($img) {
            if (!$img) return null;
            if (str_starts_with($img, 'http')) return $img;
            return asset('uploads/' . $img);
        }, $this->gallery_images)));
    }


    protected $attributes = [
        'active' => true,
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Get the attribute values associated with this variant.
     */
    public function attributeValues()
    {
        return $this->belongsToMany(VariantAttributeValue::class, 'product_variant_attribute_values', 'variant_id', 'value_id')
                    ->withPivot('attribute_id', 'custom_value')
                    ->withTimestamps();
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // ─── Accessors ─────────────────────────────────────────────────

    /**
     * Human readable label: "500 ml", "1 liter", "250 g"
     * [LOGIC] Enhanced to check for linked attribute values first.
     */
    public function getVariantLabelAttribute()
    {
        if ($this->variant_name) return $this->variant_name;
        
        // Try pivot table for attribute values
        $attributeValues = \DB::table('product_variant_attribute_values as pvav')
            ->join('variant_attribute_values as vav', 'pvav.value_id', '=', 'vav.id')
            ->where('pvav.variant_id', $this->id)
            ->pluck('vav.value')
            ->toArray();

        if (!empty($attributeValues)) {
            return implode(' / ', $attributeValues);
        }

        return trim($this->value . ' ' . $this->variant_unit);
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format((float)$this->discounted_price, 2);
    }

    /**
     * Logic: Calculate price after variant-level discount.
     * Rounding: PHP_ROUND_HALF_UP (2 decimal places)
     * Overflow: Minimum price is 0.
     */
    public function getDiscountedPriceAttribute()
    {
        $mrp = (float)$this->mrp_price;
        $discountValue = (float)$this->discount_value;

        if (!$this->discount_type || $discountValue <= 0) {
            return $mrp;
        }

        $finalPrice = $this->discount_type === 'percentage'
            ? $mrp * (1 - ($discountValue / 100))
            : $mrp - $discountValue;

        return max(0, round($finalPrice, 2));
    }

    public function getDiscountAmountAttribute()
    {
        return max(0, (float)$this->mrp_price - (float)$this->discounted_price);
    }


    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity > 10) return 'In Stock';
        if ($this->stock_quantity > 0)  return 'Low Stock';
        return 'Out of Stock';
    }

    public function getVariantImageAttribute($value)
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        if (filter_var($value, FILTER_VALIDATE_URL)) return $value;
        return asset('uploads/' . $value);
    }

    public function getImageUrlAttribute()
    {
        return $this->main_image;
    }

    public function getImageAttribute()
    {
        return $this->main_image;
    }

    public function getMainImageAttribute()
    {
        // 1. Try variant image
        if ($this->variant_image) {
            return $this->variant_image;
        }

        // 2. Fallback to product image
        if ($this->product && $this->product->image) {
            return $this->product->image;
        }

        // 3. Last fallback to placeholder
        return asset('assets/images/product/img-1.png');
    }
}
