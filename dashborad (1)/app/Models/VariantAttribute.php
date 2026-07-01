<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'input_type',
        'description',
        'is_required',
        'is_visible',
        'use_in_product_listing',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'use_in_product_listing' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all values for this attribute
     */
    public function values()
    {
        return $this->hasMany(VariantAttributeValue::class, 'attribute_id');
    }

    /**
     * Get active values only
     */
    public function activeValues()
    {
        return $this->hasMany(VariantAttributeValue::class, 'attribute_id')
                    ->where('is_active', true)
                    ;
    }

    /**
     * Scope for active attributes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered attributes
     */
    public function scopeOrdered($query)
    {
        return $query;
    }

    /**
     * Scope for required attributes
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope for visible attributes
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Check if this is a color attribute
     */
    public function isColorAttribute()
    {
        return $this->input_type === 'color' || $this->input_type === 'swatch';
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }
}

