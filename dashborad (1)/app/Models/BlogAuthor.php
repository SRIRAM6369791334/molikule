<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogAuthor extends Model
{
    use HasFactory;

    public function getImageFullUrlAttribute()
    {
        if (!$this->image_url) {
            return asset('uploads/blogs/author-1.png');
        }

        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        if (\Illuminate\Support\Str::startsWith($this->image_url, 'assets/')) {
            $filename = basename($this->image_url);
            return asset('uploads/blogs/' . $filename);
        }

        return asset('uploads/' . $this->image_url);
    }

    protected $fillable = [
        'name',
        'designation',
        'bio',
        'image_url',
        'social_links'
    ];

    protected $casts = [
        'social_links' => 'array'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }
}
