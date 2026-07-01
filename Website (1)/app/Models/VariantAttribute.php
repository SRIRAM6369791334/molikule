<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    protected $table = 'variant_attributes';

    protected $fillable = [
        'name', 'slug', 'input_type', 'description',
        'is_required', 'is_visible', 'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_visible'  => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function values()
    {
        return $this->hasMany(VariantAttributeValue::class, 'attribute_id');
    }

    public function activeValues()
    {
        return $this->hasMany(VariantAttributeValue::class, 'attribute_id')
                    ->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
