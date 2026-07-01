<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Schema::hasTable('banners')
            ? Banner::active()->homepage()->ordered()->get()
            : collect();

        $categories = Schema::hasTable('categories')
            ? Category::active()->topLevel()
                ->when(
                    Schema::hasTable('products'),
                    fn($query) => $query->withCount([
                        'products' => fn($productQuery) => $productQuery->where('active', true),
                    ])
                )
                ->orderBy('sort_order')
                ->get()
            : collect();

        $featuredProducts = Schema::hasTable('products')
            ? Product::active()->featured()
                ->with(['category', 'brand', 'variants'])
                ->take(16)
                ->get()
            : collect();

        $trendingProducts = Schema::hasTable('products')
            ? Product::active()->trending()
                ->with(['category', 'brand', 'variants'])
                ->take(16)
                ->get()
            : collect();

        // If trending is empty, fall back to slicing featured as before (optional, but keep for now)
        if ($trendingProducts->isEmpty() && $featuredProducts->count() > 8) {
            $trendingProducts = $featuredProducts->slice(8);
            $featuredProducts = $featuredProducts->take(8);
        } else {
            $featuredProducts = $featuredProducts->take(8);
        }

        // Senior Dev Note: Diversify latest products to ensure each category has representation
        $latestProducts = collect();
        if (Schema::hasTable('products') && Schema::hasTable('categories')) {
            $topCategories = Category::active()->topLevel()->take(6)->get();
            foreach ($topCategories as $category) {
                // Senior Dev Fix: Get the category ID and all its children IDs to ensure subcategory products appear
                $categoryIds = $category->children()->pluck('category_id')->push($category->category_id);

                $catProducts = Product::active()
                    ->whereIn('category_id', $categoryIds)
                    ->latest()
                    ->with(['category', 'variants'])
                    ->take(3) // Increased to 3 to ensure a fuller grid
                    ->get();
                $latestProducts = $latestProducts->merge($catProducts);
            }
            
            // If we don't have enough products after category-wise fetch, fill with latest overall
            if ($latestProducts->count() < 12) {
                $remainingCount = 12 - $latestProducts->count();
                $extraProducts = Product::active()
                    ->whereNotIn('product_id', $latestProducts->pluck('product_id'))
                    ->latest()
                    ->with(['category', 'variants'])
                    ->take($remainingCount)
                    ->get();
                $latestProducts = $latestProducts->merge($extraProducts);
            }
            
            // Senior Dev Note: Remove duplicates and maintain the 12 item limit for layout integrity
            $latestProducts = $latestProducts->unique('product_id')->take(12);
        }

        $brands = Schema::hasTable('brands')
            ? Brand::active()
                ->orderBy('sort_order')
                ->take(12)
                ->get()
            : collect();

        $latestBlogs = Schema::hasTable('blogs')
            ? \App\Models\Blog::active()->with(['category', 'author'])->latest()->take(3)->get()
            : collect();

        $bentoCards = Schema::hasTable('bento_cards')
            ? \App\Models\BentoCard::active()->ordered()->get()
            : collect();

        return view('pages.home', compact(
            'banners',
            'categories',
            'featuredProducts',
            'trendingProducts',
            'latestProducts',
            'brands',
            'latestBlogs',
            'bentoCards'
        ));
    }
    public function newHome()
    {
        $banners = Schema::hasTable('banners')
            ? Banner::active()->homepage()->ordered()->get()
            : collect();

        $categories = Schema::hasTable('categories')
            ? Category::active()->topLevel()
                ->when(
                    Schema::hasTable('products'),
                    fn($query) => $query->withCount([
                        'products' => fn($productQuery) => $productQuery->where('active', true),
                    ])
                )
                ->orderBy('sort_order')
                ->get()
            : collect();

        $featuredProducts = Schema::hasTable('products')
            ? Product::active()->featured()
                ->with(['category', 'brand', 'variants'])
                ->take(16)
                ->get()
            : collect();

        $trendingProducts = Schema::hasTable('products')
            ? Product::active()->trending()
                ->with(['category', 'brand', 'variants'])
                ->take(16)
                ->get()
            : collect();

        // If trending is empty, fall back to slicing featured as before (optional, but keep for now)
        if ($trendingProducts->isEmpty() && $featuredProducts->count() > 8) {
            $trendingProducts = $featuredProducts->slice(8);
            $featuredProducts = $featuredProducts->take(8);
        } else {
            $featuredProducts = $featuredProducts->take(8);
        }

        $latestProducts = Schema::hasTable('products')
            ? Product::active()
                ->latest()
                ->with(['category', 'variants'])
                ->take(8)
                ->get()
            : collect();

        $brands = Schema::hasTable('brands')
            ? Brand::active()
                ->orderBy('sort_order')
                ->take(12)
                ->get()
            : collect();

        $latestBlogs = Schema::hasTable('blogs')
            ? \App\Models\Blog::active()->with(['category', 'author'])->latest()->take(3)->get()
            : collect();

        return view('pages.new', compact(
            'banners',
            'categories',
            'featuredProducts',
            'trendingProducts',
            'latestProducts',
            'brands',
            'latestBlogs'
        ));
    }

    public function careers()
    {
        $positions = Schema::hasTable('job_positions')
            ? \App\Models\JobPosition::where('is_active', true)->orderBy('title')->get()
            : collect();

        return view('pages.careers', compact('positions'));
    }

    public function about()
    {
        $certificates = \App\Models\Certificate::active()->ordered()->get();
        $categories = Schema::hasTable('categories')
            ? Category::active()->topLevel()->orderBy('sort_order')->take(6)->get()
            : collect();

        return view('pages.about', compact('certificates', 'categories'));
    }
}
