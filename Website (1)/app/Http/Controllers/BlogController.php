<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with(['category', 'author', 'tags'])->where('is_published', true);

        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('author')) {
            $query->where('author_id', $request->author);
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->latest('published_at')->paginate(9);
        $categories = BlogCategory::where('active', true)->withCount('blogs')->get();
        $recent_posts = Blog::where('is_published', true)->latest('published_at')->take(3)->get();
        $tags = BlogTag::all();

        return view('pages.blog', compact('blogs', 'categories', 'recent_posts', 'tags'));
    }

    public function show($slug)
    {
        $blog = Blog::with(['category', 'author', 'tags'])->where('slug', $slug)->firstOrFail();
        
        // Increment view count
        $blog->increment('view_count');

        $categories = BlogCategory::where('active', true)->withCount('blogs')->get();
        $recent_posts = Blog::where('is_published', true)->where('id', '!=', $blog->id)->latest('published_at')->take(3)->get();
        $tags = BlogTag::all();

        return view('pages.blog-details', compact('blog', 'categories', 'recent_posts', 'tags'));
    }
}
