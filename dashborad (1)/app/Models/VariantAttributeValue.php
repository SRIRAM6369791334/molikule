<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'display_name',
        'short_code',
        'color_code',
        'image_url',
        'price_modifier',
        'price_modifier_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the attribute this value belongs to
     */
    public function attribute()
    {
        return $this->belongsTo(VariantAttribute::class, 'attribute_id');
    }

    /**
     * Scope for active values
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered values
     */
    public function scopeOrdered($query)
    {
        return $query;
    }

    /**
     * Get display name (fallback to value if not set)
     */
    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->value;
    }

    /**
     * Check if this value has a color code
     */
    public function hasColorCode()
    {
        return !empty($this->color_code);
    }

    /**
     * Check if this value has an image
     */
    public function hasImage()
    {
        return !empty($this->image_url);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Otherwise, assume it's stored in storage
        return \Storage::url($value);
    }

    /**
     * Calculate price with modifier
     */
    public function calculatePrice($basePrice)
    {
        if ($this->price_modifier == 0) {
            return $basePrice;
        }

        if ($this->price_modifier_type === 'percentage') {
            return $basePrice + ($basePrice * ($this->price_modifier / 100));
        }

        // Fixed modifier
        return $basePrice + $this->price_modifier;
    }

    /**
     * Get formatted price modifier for display
     */
    public function getFormattedPriceModifierAttribute()
    {
        if ($this->price_modifier == 0) {
            return null;
        }

        $sign = $this->price_modifier > 0 ? '+' : '';

        if ($this->price_modifier_type === 'percentage') {
            return $sign . number_format($this->price_modifier, 0) . '%';
        }

        return $sign . '₹' . number_format($this->price_modifier, 2);
    }
}

