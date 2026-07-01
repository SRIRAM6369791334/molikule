<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $table = 'product_variants';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_id',
        'variant_name',
        'variant_type',
        'value',
        'sku',
        'barcode',
        'low_stock',
        'unit_id',
        'offer_price',
        'variant_unit',
        'mrp_price',
        'compare_price',
        'stock_quantity',
        'variant_image',
        'active',
        'is_featured',
        'is_trending',
        'discount_type',
        'discount_value',
    ];

    protected $casts = [
        'mrp_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'active' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            VariantAttributeValue::class,
            'product_variant_attribute_values',
            'variant_id',
            'value_id'
        )->withPivot('attribute_id');
    }

    public function getVariantLabelAttribute()
    {
        if ($this->variant_name) {
            return $this->variant_name;
        }

        // Try pivot table for multi-attribute values
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

    public function getVariantImageFullUrlAttribute()
    {
        if (!$this->variant_image) {
            return null;
        }

        $rawImage = $this->getRawOriginal('variant_image');
        return productImageUrl($rawImage);
    }

    public function getFormattedPriceAttribute()
    {
        return formatPrice($this->discounted_price);
    }

    public function getDiscountedPriceAttribute()
    {
        $mrp = (float) $this->mrp_price;
        $discountValue = (float) $this->discount_value;

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
        return max(0, (float) $this->mrp_price - (float) $this->discounted_price);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
}
