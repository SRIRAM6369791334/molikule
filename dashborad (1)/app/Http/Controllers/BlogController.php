<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogAuthor;
use App\Models\BlogTag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::with(['category', 'author'])->latest()->paginate(50);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'blogs' => $blogs
            ]);
        }

        return view('blogs', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::where('active', true)->get();
        $authors = BlogAuthor::all();
        return view('add-blog', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'category_name' => 'required|string|min:2|max:100',
            'author_name' => 'required|string|min:2|max:100',
            'excerpt' => 'required|string|min:20|max:500',
            'content' => 'required|string|min:50',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:100',
            'tags_input' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $validated['category_id'] = $this->resolveCategoryId($request->category_name);
            $validated['author_id'] = $this->resolveAuthorId($request->author_name);
            $validated['slug'] = (new Blog)->generateUniqueSlug($validated['title']);
            
            if ($request->hasFile('image')) {
                $validated['image_url'] = $request->file('image')->store('blogs', 'uploads');
            }

            $validated['is_published'] = $request->has('is_published');
            if ($validated['is_published'] && !$request->has('published_at')) {
                $validated['published_at'] = now();
            }

            $blog = Blog::create($validated);
            $blog->tags()->sync($this->resolveTagIds($this->extractTags($request)));

            DB::commit();
            return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create blog. ' . $e->getMessage()]);
        }
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::where('active', true)->get();
        $authors = BlogAuthor::all();
        return view('edit-blog', compact('blog', 'categories', 'authors'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'category_name' => 'required|string|min:2|max:100',
            'author_name' => 'required|string|min:2|max:100',
            'excerpt' => 'required|string|min:20|max:500',
            'content' => 'required|string|min:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:100',
            'tags_input' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $validated['category_id'] = $this->resolveCategoryId($request->category_name);
            $validated['author_id'] = $this->resolveAuthorId($request->author_name);
            $validated['slug'] = $blog->generateUniqueSlug($validated['title']);
            
            if ($request->hasFile('image')) {
                if ($blog->image_url && Storage::disk('uploads')->exists($blog->image_url)) {
                    Storage::disk('uploads')->delete($blog->image_url);
                }
                $validated['image_url'] = $request->file('image')->store('blogs', 'uploads');
            }

            $validated['is_published'] = $request->has('is_published');
             
            $blog->update($validated);
            $blog->tags()->sync($this->resolveTagIds($this->extractTags($request)));

            DB::commit();
            return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update blog. ' . $e->getMessage()]);
        }
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image_url && Storage::disk('uploads')->exists($blog->image_url)) {
            Storage::disk('uploads')->delete($blog->image_url);
        }
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }

    public function toggleStatus(Blog $blog)
    {
        $blog->update(['is_published' => !$blog->is_published]);
        return response()->json(['success' => true, 'is_published' => $blog->is_published]);
    }

    protected function resolveTagIds(array $tags): array
    {
        return collect($tags)
            ->map(fn ($tag) => is_string($tag) ? trim($tag) : $tag)
            ->filter(fn ($tag) => filled($tag))
            ->map(function ($tag) {
                if (is_numeric($tag)) {
                    $existingTag = BlogTag::find((int) $tag);
                    if ($existingTag) {
                        return $existingTag->id;
                    }
                }

                $tagName = trim((string) $tag);
                $slug = Str::slug($tagName);

                if ($slug === '') {
                    return null;
                }

                $blogTag = BlogTag::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $tagName]
                );

                return $blogTag->id;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function extractTags(Request $request): array
    {
        $selectedTags = $request->input('tags', []);
        if (!is_array($selectedTags)) {
            $selectedTags = [];
        }

        $manualTags = collect(explode(',', (string) $request->input('tags_input', '')))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        return array_merge($selectedTags, $manualTags);
    }

    protected function resolveCategoryId($name)
    {
        $name = trim($name);
        if (empty($name)) return null;
        
        $slug = Str::slug($name);
        $category = BlogCategory::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'active' => true]
        );
        return $category->id;
    }

    protected function resolveAuthorId($name)
    {
        $name = trim($name);
        if (empty($name)) return null;

        $author = BlogAuthor::firstOrCreate(
            ['name' => $name],
            ['designation' => 'Author']
        );
        return $author->id;
    }
}
