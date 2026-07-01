<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BentoCard extends Model
{
    protected $table = 'bento_cards';

    protected $fillable = [
        'tag', 'title', 'description', 'image_path', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Returns the publicly accessible image URL using MAIN_URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return rtrim(env('MAIN_URL'), '/') . '/uploads/bento_cards/' . ltrim($this->image_path, '/');
        }
        return asset('assets/images/placeholder.png');
    }
}
