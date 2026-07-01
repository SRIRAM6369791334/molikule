<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'image',
        'is_active',
        'slug',
        'parent_id',
        'sort_order',
        'is_featured',
        'show_on_homepage',
        'theme_primary_color',
        'theme_light_color',
        'theme_light_opacity',
        'theme_bg_image',
        'theme_bg_overlay',
        'theme_bg_opacity',
        'theme_border_radius',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'category_id';
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('category_name');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    /**
     * Scope for filtering categories based on product presence
     */
    public function scopeHasProducts($query, bool $hasProducts = true)
    {
        return $hasProducts
            ? $query->has('products')
            : $query->doesntHave('products');
    }

    public function scopeOrdered($query)
    {

        return $query->orderBy('sort_order')->orderBy('category_name');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('category_name', 'like', "%{$search}%");
    }

    public function scopeFilterByStatus($query, string $status)
    {
        if ($status === 'active')
            return $query->where('is_active', true);
        if ($status === 'inactive')
            return $query->where('is_active', false);
        return $query;
    }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->category_name));
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge bg-success rounded-pill px-3"><i class="bx bx-check-circle me-1"></i>Active</span>'
            : '<span class="badge bg-secondary rounded-pill px-3"><i class="bx bx-x-circle me-1"></i>Inactive</span>';
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('uploads/' . $value);
    }

    public function getImageUrlAttribute()
    {
        return $this->image;
    }

    public function setImageUrlAttribute($value)
    {
        $this->attributes['image'] = $value;
    }

    public function getProductsCountAttribute()
    {
        if (array_key_exists('products_count', $this->attributes) && $this->attributes['products_count'] !== null) {
            return $this->attributes['products_count'];
        }
        return $this->products()->count();
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->where('category_id', '!=', $this->category_id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        return $slug;
    }

    public function canBeDeleted(): bool
    {
        return $this->products()->count() === 0;
    }

    public static function getFeaturedCategories()
    {
        return self::active()->featured()->ordered()->get();
    }
}
