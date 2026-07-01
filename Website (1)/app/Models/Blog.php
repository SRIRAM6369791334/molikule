<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_blog_tag');
    }

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image_url',
        'is_published',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(BlogAuthor::class, 'author_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true);
    }

    public function getImageFullUrlAttribute()
    {
        $mainUrl = rtrim(env('MAIN_URL'), '/');
        
        if (!$this->image_url) {
            return $mainUrl . '/uploads/blogs/news-1.jpg';
        }

        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        if (Str::startsWith($this->image_url, 'assets/')) {
            // If it still has legacy assets path, try to map it to the new location
            $filename = basename($this->image_url);
            return $mainUrl . '/uploads/blogs/' . $filename;
        }

        return $mainUrl . '/uploads/' . $this->image_url;
    }

    public function generateUniqueSlug($title)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        while (self::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        return $slug;
    }
}
