<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class BlogAuthor extends Model
{
    use HasFactory;

    public function getImageFullUrlAttribute()
    {
        $mainUrl = rtrim(env('MAIN_URL'), '/');
        
        if (!$this->image_url) {
            return $mainUrl . '/uploads/blogs/author-1.png';
        }

        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        if (Str::startsWith($this->image_url, 'assets/')) {
            $filename = basename($this->image_url);
            return $mainUrl . '/uploads/blogs/' . $filename;
        }

        return $mainUrl . '/uploads/' . $this->image_url;
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
