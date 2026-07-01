<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    protected $table = 'certificates';

    protected $fillable = [
        'title', 'image_path', 'sort_order', 'is_active',
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
     * Returns the publicly accessible image URL.
     * The Dashboard stores files at:  uploads/certificates/<filename>
     * The Website's public symlink:   public/uploads -> ../molikuleDashboard/storage/uploads
     *
     * Adjust the asset path below to match your actual symlink/setup.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return rtrim(env('MAIN_URL'), '/') . '/uploads/' . ltrim($this->image_path, '/');
        }
        return asset('assets/images/certificates/placeholder.png');
    }
}
