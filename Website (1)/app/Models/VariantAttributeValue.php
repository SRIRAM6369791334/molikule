<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    protected $table = 'variant_attribute_values';

    protected $fillable = [
        'attribute_id', 'value', 'slug', 'display_name',
        'short_code', 'color_code', 'image_url',
        'price_modifier', 'price_modifier_type',
        'description', 'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function attribute()
    {
        return $this->belongsTo(VariantAttribute::class, 'attribute_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get display name (fallback to value if not set)
     */
    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->value;
    }
}
