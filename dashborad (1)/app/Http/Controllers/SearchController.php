<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Search across products, categories, and brands
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, products, categories, brands
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
                'results' => []
            ]);
        }

        $results = [];
        
        // Search in products
        if ($type === 'all' || $type === 'products') {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->select(['product_id', 'slug', 'name', 'description', 'mrp_price', 'active'])
                ->limit(10)
                ->get();
                
            foreach ($products as $product) {
                $results[] = [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'type' => 'product',
                    'url' => route('products.show', $product->slug),
                    'mrp_price' => $product->mrp_price,
                    'status' => $product->active ? 'active' : 'inactive'
                ];
            }
        }

        // Search in categories
        if ($type === 'all' || $type === 'categories') {
            $categories = Category::where('category_name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->select(['category_id', 'slug', 'category_name', 'description', 'is_active'])
                ->limit(10)
                ->get();
                
            foreach ($categories as $category) {
                $results[] = [
                    'id' => $category->category_id,
                    'name' => $category->category_name,
                    'description' => $category->description,
                    'type' => 'category',
                    'url' => route('categories.show', $category->slug),
                    'status' => $category->is_active ? 'active' : 'inactive'
                ];
            }
        }

        // Search in brands
        if ($type === 'all' || $type === 'brands') {
            $brands = Brand::where('brand_name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->select(['brand_id', 'slug', 'brand_name', 'description', 'is_active'])
                ->limit(10)
                ->get();
                
            foreach ($brands as $brand) {
                $results[] = [
                    'id' => $brand->brand_id,
                    'name' => $brand->brand_name,
                    'description' => $brand->description,
                    'type' => 'brand',
                    'url' => route('brands.show', $brand->slug),
                    'status' => $brand->is_active ? 'active' : 'inactive'
                ];
            }
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * Show search results page
     */
    public function showResults(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('dashboard')->with('error', 'Search query is required');
        }

        // 1. Check counts to determine the best destination
        $productCount = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('sku', 'LIKE', "%{$query}%")
            ->count();

        $categoryCount = Category::where('category_name', 'LIKE', "%{$query}%")->count();
        $brandCount = Brand::where('brand_name', 'LIKE', "%{$query}%")->count();

        // 2. Decision Logic
        if ($productCount > 0) {
            // If there are products, always go to products index (Most important)
            return redirect()->route('products.index', ['q' => $query]);
        } elseif ($categoryCount > 0) {
            // If only categories match, go to categories index
            return redirect()->route('categories.index', ['q' => $query]);
        } elseif ($brandCount > 0) {
            // If only brands match, go to brands index
            return redirect()->route('brands.index', ['q' => $query]);
        }

        // 3. Fallback: Default to Products index even if 0 results
        return redirect()->route('products.index', ['q' => $query]);
    }

    /**
     * Get search suggestions for autocomplete
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = [];
        
        // Product suggestions
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->select(['product_id', 'slug', 'name'])
            ->limit(5)
            ->get();
            
        foreach ($products as $product) {
            $suggestions[] = [
                'text' => $product->name,
                'type' => 'product',
                'url' => route('products.index', ['q' => $product->name])
            ];
        }

        // Category suggestions
        $categories = Category::where('category_name', 'LIKE', "%{$query}%")
            ->select(['category_id', 'category_name'])
            ->limit(3)
            ->get();
            
        foreach ($categories as $category) {
            $suggestions[] = [
                'text' => $category->category_name,
                'type' => 'category',
                'url' => route('categories.index', ['q' => $category->category_name])
            ];
        }

        // Brand suggestions
        $brands = Brand::where('brand_name', 'LIKE', "%{$query}%")
            ->select(['brand_id', 'brand_name'])
            ->limit(3)
            ->get();
            
        foreach ($brands as $brand) {
            $suggestions[] = [
                'text' => $brand->brand_name,
                'type' => 'brand',
                'url' => route('brands.index', ['q' => $brand->brand_name])
            ];
        }

        return response()->json($suggestions);
    }
}