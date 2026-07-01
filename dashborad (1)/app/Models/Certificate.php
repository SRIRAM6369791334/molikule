<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Scopes ──────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // ── Accessors ────────────────────────────────────────────
    /**
     * Full public URL for the certificate image.
     * Falls back to a placeholder if none uploaded.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('uploads')->exists($this->image_path)) {
            return asset('uploads/' . $this->image_path);
        }
        return asset('assets/images/certificates/placeholder.png');
    }
}
