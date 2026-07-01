<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where('category_name', 'LIKE', "%{$searchTerm}%");
        }

        $categories = $query->get();
        return view('categories.index', compact('categories'));
    }

    public function ajaxIndex()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'category_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:width=512,height=512',
            'theme_primary_color' => 'nullable|string|max:50',
            'theme_light_color' => 'nullable|string|max:50',
            'theme_bg_overlay' => 'nullable|string|max:50',
            'theme_border_radius' => 'nullable|string|max:50',
            'theme_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $categoryData = [
            'category_name' => $validated['category_name'],
            'slug' => Str::slug($validated['category_name']),
            'is_active' => $request->boolean('is_active', true),
            'theme_primary_color' => $validated['theme_primary_color'] ?? null,
            'theme_light_color' => $validated['theme_light_color'] ?? null,
            'theme_bg_overlay' => $validated['theme_bg_overlay'] ?? null,
            'theme_border_radius' => $validated['theme_border_radius'] ?? null,
        ];
        
        if ($request->hasFile('category_image')) {
            $categoryData['image'] = $request->file('category_image')->store('categories', 'uploads');
        }

        if ($request->hasFile('theme_bg_image')) {
            $categoryData['theme_bg_image'] = $request->file('theme_bg_image')->store('categories/themes', 'uploads');
        }

        $category = Category::create($categoryData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category,
                'categories' => Category::all()
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->category_id . ',category_id',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:width=512,height=512',
            'theme_primary_color' => 'nullable|string|max:50',
            'theme_light_color' => 'nullable|string|max:50',
            'theme_bg_overlay' => 'nullable|string|max:50',
            'theme_border_radius' => 'nullable|string|max:50',
            'theme_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $categoryData = [
            'category_name' => $validated['category_name'],
            'slug' => Str::slug($validated['category_name']),
            'is_active' => $request->boolean('is_active', $category->is_active),
            'theme_primary_color' => $validated['theme_primary_color'] ?? null,
            'theme_light_color' => $validated['theme_light_color'] ?? null,
            'theme_bg_overlay' => $validated['theme_bg_overlay'] ?? null,
            'theme_border_radius' => $validated['theme_border_radius'] ?? null,
        ];

        if ($request->hasFile('category_image')) {
            if ($category->image && Storage::disk('uploads')->exists($category->image)) {
                Storage::disk('uploads')->delete($category->image);
            }
            $categoryData['image'] = $request->file('category_image')->store('categories', 'uploads');
        }

        if ($request->hasFile('theme_bg_image')) {
            if ($category->theme_bg_image && Storage::disk('uploads')->exists($category->theme_bg_image)) {
                Storage::disk('uploads')->delete($category->theme_bg_image);
            }
            $categoryData['theme_bg_image'] = $request->file('theme_bg_image')->store('categories/themes', 'uploads');
        }

        $category->update($categoryData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category,
                'categories' => Category::all()
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if category has products
        $productCount = $category->products()->count();
        if ($productCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category containing ' . $productCount . ' products. Move or delete them first.'
            ], 400);
        }

        // Use raw original to get the path without the accessor's full URL
        $imagePath = $category->getRawOriginal('image');
        if ($imagePath && Storage::disk('uploads')->exists($imagePath)) {
            Storage::disk('uploads')->delete($imagePath);
        }

        $themeImagePath = $category->getRawOriginal('theme_bg_image');
        if ($themeImagePath && Storage::disk('uploads')->exists($themeImagePath)) {
            Storage::disk('uploads')->delete($themeImagePath);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
            'categories' => Category::all()
        ]);
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'is_active' => $category->is_active
        ]);
    }
}
