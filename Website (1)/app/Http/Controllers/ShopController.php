<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of products with filtering and sorting.
     */
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'brand', 'variants']);
        $currentCategory = null;

        // Filter by Category
        if ($request->filled('category')) {
            $currentCategory = Category::where('slug', $request->category)
                ->orWhere('category_id', $request->category)
                ->first();

            if ($currentCategory) {
                // Get this category and all its children IDs
                $categoryIds = [$currentCategory->category_id];
                $categoryIds = array_merge($categoryIds, $currentCategory->children()->pluck('category_id')->toArray());
                
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Filter by Brand
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand)->orWhere('brand_id', $request->brand);
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('mrp_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('mrp_price', '<=', $request->max_price);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting & Filtering
        $sort = $request->get('sort', 'price_desc');
        match ($sort) {
            'price_asc'  => $query->orderBy('mrp_price', 'asc'),
            'price_desc' => $query->orderBy('mrp_price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            'trending'   => $query->where('is_trending', true)->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
            'featured'   => $query->where('is_featured', true)->orderBy('is_trending', 'desc')->orderBy('created_at', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderBy('mrp_price', 'desc'),
        };

        $products = $query->paginate(12)->withQueryString();

        // Sidebar data
        $categories = Category::active()
            ->topLevel()
            ->withCount(['products' => fn($q) => $q->where('active', true)])
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::active()
            ->withCount(['products' => fn($q) => $q->where('active', true)])
            ->orderBy('sort_order')
            ->get();

        $priceRange = [
            'min' => (int) (Product::active()->min('mrp_price') ?? 0),
            'max' => (int) (Product::active()->max('mrp_price') ?? 5000),
        ];

        return view('pages.shop', compact('products', 'categories', 'brands', 'priceRange', 'sort', 'currentCategory'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load([
            'category',
            'brand',
            'variants' => fn($q) => $q->where('active', true)->orderBy('mrp_price'),
            'variants.attributeValues.attribute',
            'reviews' => fn($q) => $q->where('is_approved', true)->with('user', 'variant'),
        ]);

        // Build variant matrix for multi-dimensional selection
        $variantMatrix = $this->buildVariantMatrix($product);

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->with(['category', 'variants'])
            ->take(4)->get();

        $canReview = false;
        $pendingReview = false;
        if (auth()->check()) {
            $user = auth()->user();
            $hasBought = \App\Models\Order::where('user_id', $user->id)
                ->where('status', \App\Models\Order::STATUS_DELIVERY)
                ->whereHas('orderItems', function ($query) use ($product) {
                    $query->where(function ($q) use ($product) {
                        $q->where('itemable_type', 'App\Models\Product')
                          ->where('itemable_id', $product->product_id);
                    })->orWhere(function ($q) use ($product) {
                        $q->where('itemable_type', 'App\Models\ProductVariant')
                          ->whereIn('itemable_id', function ($sub) use ($product) {
                              $sub->select('id')->from('product_variants')->where('product_id', $product->product_id);
                          });
                    });
                })->exists();

            $pendingReview = \App\Models\ProductReview::where('user_id', $user->id)
                ->where('product_id', $product->product_id)
                ->where('is_approved', false)
                ->exists();

            $alreadyReviewed = \App\Models\ProductReview::where('user_id', $user->id)
                ->where('product_id', $product->product_id)
                ->where('is_approved', true)
                ->exists();

            $canReview = $hasBought && !$alreadyReviewed && !$pendingReview;
        }

        return view('pages.shop-details', compact('product', 'relatedProducts', 'variantMatrix', 'canReview', 'pendingReview'));
    }

    /**
     * Build variant combination matrix for multi-dimensional frontend selector.
     * Output: ['attributes' => [...], 'combinations' => [...]]
     */
    private function buildVariantMatrix(Product $product): array
    {
        $attributes = [];
        $combinations = [];

        foreach ($product->variants as $variant) {
            $combo = [];

            // 1. Check for traditional attributes (pivot table)
            if ($variant->attributeValues->count() > 0) {
                foreach ($variant->attributeValues as $attrVal) {
                    $attrName = $attrVal->attribute->name ?? 'Unknown';
                    $attrId = $attrVal->attribute->id ?? 0;

                    if (!isset($attributes[$attrId])) {
                        $attributes[$attrId] = ['id' => $attrId, 'name' => $attrName, 'options' => []];
                    }

                    $existingValues = array_column($attributes[$attrId]['options'], 'id');
                    if (!in_array($attrVal->id, $existingValues)) {
                        $attributes[$attrId]['options'][] = ['id' => $attrVal->id, 'value' => $attrVal->value];
                    }
                    $combo[$attrId] = $attrVal->id;
                }
            } 

            // 2. SUPPORT NEW MATRIX: Flavour (value) and Volume (variant_unit)
            // Handle Flavour
            if ($variant->value) {
                $attrId = 'flavour';
                if (!isset($attributes[$attrId])) {
                    $attributes[$attrId] = ['id' => $attrId, 'name' => 'Flavour', 'options' => []];
                }
                $existing = array_column($attributes[$attrId]['options'], 'value');
                if (!in_array($variant->value, $existing)) {
                    $attributes[$attrId]['options'][] = ['id' => $variant->value, 'value' => $variant->value];
                }
                $combo[$attrId] = $variant->value;
            }

            // Handle Volume
            if ($variant->variant_unit) {
                $attrId = 'volume';
                if (!isset($attributes[$attrId])) {
                    $attributes[$attrId] = ['id' => $attrId, 'name' => 'Volume', 'options' => []];
                }
                $existing = array_column($attributes[$attrId]['options'], 'value');
                if (!in_array($variant->variant_unit, $existing)) {
                    $attributes[$attrId]['options'][] = ['id' => $variant->variant_unit, 'value' => $variant->variant_unit];
                }
                $combo[$attrId] = $variant->variant_unit;
            }

            // 3. FALLBACK: Option (variant_name) if no attributes found
            if (empty($combo) && $variant->variant_name) {
                $attrId = 'option';
                if (!isset($attributes[$attrId])) {
                    $attributes[$attrId] = ['id' => $attrId, 'name' => 'Option', 'options' => []];
                }
                $existing = array_column($attributes[$attrId]['options'], 'value');
                if (!in_array($variant->variant_name, $existing)) {
                    $attributes[$attrId]['options'][] = ['id' => $variant->variant_name, 'value' => $variant->variant_name];
                }
                $combo[$attrId] = $variant->variant_name;
            }

            $combinations[] = [
                'variant_id' => $variant->id,
                'attributes' => $combo,
                'price'      => (float) $variant->discounted_price,
                'mrp_price'  => (float) $variant->mrp_price,
                'stock'      => $variant->stock_quantity,
                'image'      => $variant->variant_image_full_url,
                'label'      => $variant->variant_label,
            ];
        }

        return [
            'attributes'   => array_values($attributes),
            'combinations' => $combinations,
        ];
    }

    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::active()
            ->topLevel()
            ->withCount(['products' => fn($q) => $q->where('active', true)])
            ->orderBy('sort_order')
            ->paginate(12);

        return view('pages.categories', compact('categories'));
    }

    /**
     * Display all brands.
     */
    public function brands()
    {
        $brands = Brand::active()
            ->withCount(['products' => fn($q) => $q->where('active', true)])
            ->orderBy('sort_order')
            ->paginate(12);

        return view('pages.brands', compact('brands'));
    }
}
