<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\InventoryTransaction;

use App\Services\ProductService;
use App\Services\AnalyticsService;
use App\Services\CacheService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $productService;
    private $analyticsService;
    private $cacheService;

    public function __construct(
        ProductService $productService,
        AnalyticsService $analyticsService,
        CacheService $cacheService
    ) {
        $this->productService = $productService;
        $this->analyticsService = $analyticsService;
        $this->cacheService = $cacheService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sku', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        $products = $query->latest()->get()->values();
        return view('products.index', compact('products'));
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'brand'])
            ->where('stock_quantity', '<=', 10)
            ->get();
        return view('products.low-stock', compact('products'));
    }

    public function ajaxIndex(Request $request)
    {
        $query = Product::with(['category', 'brand']);
        
        if ($request->has('lowStock')) {
            $query->where('stock_quantity', '<=', 10);
        }

        $products = $query->get();
        return response()->json($products);
    }

    public function stocks()
    {
        return view('products.stocks');
    }

    public function stocksAjax(Request $request)
    {
        $query = ProductVariant::with(['product.category', 'product.brand']);
        
        $filter = $request->get('filter', 'all');

        $variants = $query->get()->map(function($variant) {
            // Calculate Sold
            $sold = \DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'Cancelled')
                ->where('order_items.itemable_type', \App\Models\ProductVariant::class)
                ->where('order_items.itemable_id', $variant->id)
                ->sum('order_items.quantity');

            return [
                'id' => $variant->id,
                'name' => $variant->product->name . ' (' . ($variant->variant_name ?: 'Standard') . ')',
                'category' => $variant->product->category->category_name ?? 'N/A',
                'available' => (int)$variant->stock_quantity,
                'sold' => (int)$sold,
                'image' => $variant->main_image
            ];
        });

        // Apply filtering
        if ($filter === 'low') {
            $variants = $variants->filter(function($v) {
                return $v['available'] > 0 && $v['available'] <= 10;
            })->values();
        } elseif ($filter === 'out') {
            $variants = $variants->filter(function($v) {
                return $v['available'] === 0;
            })->values();
        }

        return response()->json($variants);
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer',
            'type' => 'required|in:add,reduce',
            'note' => 'nullable|string|max:255'
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);
        $adjustment = (int)$request->quantity;
        
        if ($request->type === 'reduce' && $variant->stock_quantity < $adjustment) {
            return response()->json([
                'success' => false, 
                'message' => "Insufficient stock. Current: {$variant->stock_quantity}, requested reduction: {$adjustment}"
            ], 400);
        }

        $finalAdjustment = $request->type === 'add' ? $adjustment : -$adjustment;
        
        DB::beginTransaction();
        try {
            $variant->stock_quantity += $finalAdjustment;
            $variant->save();
            
            // Sync main product stock
            if ($variant->product) {
                // Also prevent master product from going negative using model hook
                $variant->product->stock_quantity += $finalAdjustment;
                $variant->product->save();
            }

            InventoryTransaction::create([
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'type' => 'adjustment',
                'quantity' => $adjustment,
                'note' => $request->note ?: 'Manual Adjustment',
                'created_by' => auth()->id()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Stock updated successfully', 'new_quantity' => $variant->stock_quantity]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update stock: ' . $e->getMessage()], 500);
        }
    }



    public function filterStats()
    {
        return response()->json($this->productService->getFilterStats());
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = VariantAttribute::with('activeValues')->active()->get();
        return view('add-product', compact('categories', 'brands', 'attributes'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $validated['active'] = $request->boolean('active', true);
            $validated['slug'] = empty($validated['slug']) ? \Illuminate\Support\Str::slug($validated['name']) : $validated['slug'];

            if ($request->hasFile('image')) {
                $validated['image'] = $this->processAndStoreImage($request->file('image'), 'products');
            }

            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_trending'] = $request->boolean('is_trending');

            // Handle Gallery Images if present
            if ($request->hasFile('gallery_images')) {
                $gallery = [];
                foreach ($request->file('gallery_images') as $file) {
                    $gallery[] = $this->processAndStoreImage($file, 'products/gallery');
                }
                $validated['gallery_images'] = $gallery;
            }

            // 1. Create the Product
            $productData = array_intersect_key($validated, array_flip([
                'name', 'description', 'short_description', 'image', 'gallery_images', 'brand_id', 'category_id',
                'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'active', 'slug', 
                'is_featured', 'is_trending', 'part_number', 'barcode', 'made_in', 'warranty',
                'weight', 'weight_unit', 'length', 'width', 'height', 'dimension_unit'
            ]));
            
            // Sync legacy/first variant fields for indexing
            $productData['mrp_price'] = $request->input('variant_mrp', 0);
            $productData['stock_quantity'] = $request->input('variant_stock', 0);

            $product = Product::create($productData);

            // 2. Automatically Create the first Variant
            $flavour = $request->input('variant_value');
            $unit = $request->input('variant_unit');
            
            // Compose variant name e.g. "Lemon – 500ml"
            $variantName = ($flavour && $unit) ? ($flavour . ' – ' . $unit) : ($flavour ?: ($unit ?: 'Standard'));

            ProductVariant::create([
                'product_id' => $product->product_id,
                'variant_name' => $variantName,
                'variant_type' => 'flavour_volume',
                'sku' => $request->input('variant_sku'),
                'value' => $flavour,
                'variant_unit' => $unit,
                'mrp_price' => $request->input('variant_mrp', 0),
                'stock_quantity' => $request->input('variant_stock', 0),
                'discount_type' => $request->input('variant_discount_type'),
                'discount_value' => $request->input('variant_discount_value', 0) ?: 0,
                'variant_image' => $product->image, 
                'active' => true
            ]);

            // 3. Create a complete Audit Snapshot (ProductRecord)
            // This captures all values from Products, Categories, Brands, and Variants
            $category = $product->category;
            $brand = $product->brand;
            $variants = $product->variants()->get(); // Get fresh variants

            \App\Models\ProductRecord::create([
                'product_name'      => $product->name,
                'sku'               => $product->sku ?: $request->input('variant_sku'),
                'category_name'     => $category->category_name ?? 'N/A',
                'brand_name'        => $brand->brand_name ?? 'N/A',
                'product_full_data'  => $product->toArray(),
                'category_full_data' => $category ? $category->toArray() : [],
                'brand_full_data'    => $brand ? $brand->toArray() : [],
                'variants_full_data' => $variants->toArray(),
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Product and initial variant launched successfully!']);
            }

            return redirect()->route('products.index')->with('success', 'Product and initial variant launched successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded images if failure
            if (isset($validated['image'])) {
                Storage::disk('uploads')->delete($validated['image']);
            }
            
            if (isset($validated['gallery_images']) && is_array($validated['gallery_images'])) {
                foreach ($validated['gallery_images'] as $img) {
                    Storage::disk('uploads')->delete($img);
                }
            }

            // Enhanced Enterprise Logging
            \Log::error('CRITICAL: Product creation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => auth()->user() ? auth()->user()->email : 'Guest',
                'payload' => $request->except(['image', 'gallery_images', 'variants']),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Product creation failed: ' . $e->getMessage()], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Product creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle variant image from the request
     */
    private function handleVariantImageFromRequest(ProductVariant $variant, Request $request, int $variantIndex)
    {
        // Handle single variant image
        if ($request->hasFile("variants.{$variantIndex}.image")) {
            $image = $request->file("variants.{$variantIndex}.image");
            $imagePath = $this->processAndStoreImage($image, 'variants');
            $variant->variant_image = $imagePath;
            $variant->save();
        }
    }



    public function show(Product $product)
    {
        // Track product view for analytics
        $this->analyticsService->trackProductView($product->product_id, auth()->id());



        if (request()->wantsJson()) {
            return response()->json($product->load(['category', 'brand']));
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = VariantAttribute::with('activeValues')->active()->get();

        if (request()->expectsJson()) {
            return response()->json([
                'product' => $product->load('category', 'brand', 'variants.attributeValues'),
                'categories' => $categories,
                'brands' => $brands,
                'attributes' => $attributes
            ]);
        }

        return view('products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        // Safety fallback: if route model binding failed, re-fetch product from URL segment
        if (!$product->product_id) {
            $segment = $request->segment(2); // e.g., '1' or 'ld14-ultra-laundry-liquid'
            $product = Product::where('slug', $segment)
                ->orWhere('product_id', is_numeric($segment) ? (int)$segment : 0)
                ->firstOrFail();
        }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $rawImage = $product->getRawOriginal('image');
            if ($rawImage && Storage::disk('uploads')->exists($rawImage)) {
                Storage::disk('uploads')->delete($rawImage);
            }
            $validated['image'] = $this->processAndStoreImage($request->file('image'), 'products');
        }

        // Handle Gallery Images
        if ($request->hasFile('gallery_images')) {
            $gallery = $product->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $this->processAndStoreImage($file, 'products/gallery');
            }
            $validated['gallery_images'] = $gallery;
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['active']      = $request->input('active', '1') == '1';

        $product->update($validated);

        // Handle wizard-style primary variant update (Step 2 of the UI)
        if ($request->filled('variant_id')) {
            $primaryVariant = \App\Models\ProductVariant::find($request->variant_id);
            if ($primaryVariant && $primaryVariant->product_id == $product->product_id) {
                $flavour = $request->input('variant_value');
                $unit = $request->input('variant_unit');
                $variantName = ($flavour && $unit) ? ($flavour . ' – ' . $unit) : ($flavour ?: ($unit ?: 'Standard'));

                $primaryVariant->update([
                    'variant_name' => $variantName,
                    'value' => $flavour,
                    'variant_unit' => $unit,
                    'mrp_price' => $request->input('variant_mrp', $primaryVariant->mrp_price),
                    'stock_quantity' => $request->input('variant_stock', $primaryVariant->stock_quantity),
                    'sku' => $request->input('variant_sku', $primaryVariant->sku),
                    'discount_type' => $request->filled('variant_discount_type') ? $request->variant_discount_type : null,
                    'discount_value' => $request->input('variant_discount_value', 0) ?: 0,
                    'active' => $product->active,
                    'is_featured' => $product->is_featured,
                    'is_trending' => $product->is_trending,
                ]);

                // Sync main product fields for indexing/legacy support
                $product->update([
                    'mrp_price' => $primaryVariant->mrp_price,
                    'stock_quantity' => $primaryVariant->stock_quantity
                ]);
            }
        }

        // Handle variants updates
        if ($request->boolean('has_variants') && isset($validated['variants'])) {
            $submittedVariantIds = collect($validated['variants'])->pluck('variant_id')->filter()->toArray();
            
            // Delete variants that are no longer present
            $product->variants()->whereNotIn('variant_id', $submittedVariantIds)->delete();

            foreach ($validated['variants'] as $index => $variantData) {
                if (!empty($variantData['variant_id'])) {
                    // Update existing variant
                    $variant = ProductVariant::find($variantData['variant_id']);
                    if ($variant) {
                        $variantName = $variantData['variant_name'];

                        // If no variant name provided, try to generate one from attributes
                        if (empty($variantName) && isset($variantData['attributes'])) {
                            $attrValues = \App\Models\VariantAttributeValue::whereIn('id', array_values($variantData['attributes']))
                                ->orderBy('attribute_id')
                                ->pluck('value')
                                ->toArray();
                            $variantName = implode(' - ', $attrValues);
                        }

                        $variant->update([
                            'variant_name' => $variantName ?: 'Standard',
                            'variant_type' => 'mixed',
                            'mrp_price' => $variantData['mrp_price'] ?? $product->mrp_price,
                            'stock_quantity' => $variantData['stock_quantity'],
                            'active' => true
                        ]);

                        // Sync multi-attributes
                        if (isset($variantData['attributes'])) {
                            $syncData = [];
                            foreach ($variantData['attributes'] as $attrId => $valueId) {
                                if ($valueId) {
                                    $syncData[$valueId] = ['attribute_id' => $attrId];
                                }
                            }
                            $variant->attributeValues()->sync($syncData);
                        }
                    }
                } else {
                    // Create new variant
                    $variantName = $variantData['variant_name'];

                    // If no variant name provided, try to generate one from attributes
                    if (empty($variantName) && isset($variantData['attributes'])) {
                        $attrValues = \App\Models\VariantAttributeValue::whereIn('id', array_values($variantData['attributes']))
                            ->orderBy('attribute_id')
                            ->pluck('value')
                            ->toArray();
                        $variantName = implode(' - ', $attrValues);
                    }

                    $variant = ProductVariant::create([
                        'product_id' => $product->product_id,
                        'variant_name' => $variantName ?: 'Standard',
                        'variant_type' => 'mixed',
                        'mrp_price' => $variantData['mrp_price'] ?? $product->mrp_price,
                        'stock_quantity' => $variantData['stock_quantity'],
                        'active' => true
                    ]);

                    // Save multi-attributes
                    if (isset($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attrId => $valueId) {
                            if ($valueId) {
                                $variant->attributeValues()->attach($valueId, [
                                    'attribute_id' => $attrId
                                ]);
                            }
                        }
                    }
                }

                // Handle variant image if uploaded
                if ($request->hasFile("variants.{$index}.main_image")) {
                    $this->handleVariantImageFromRequest($variant, $request, $index);
                }
            }
        } else {
            // If variants disabled, ensure at least one standard variant exists or update it
            $standardVariant = $product->variants()->first();
            $variantData = [
                'variant_name' => $request->input('variant_name') ?: $product->name,
                'variant_type' => $request->input('variant_type', 'other'),
                'mrp_price' => $product->mrp_price,
                'offer_price' => $product->compare_price ?: $product->mrp_price,
                'compare_price' => $product->compare_price,
                'stock_quantity' => $product->stock_quantity,
                'active' => true,
                'is_featured' => $product->is_featured,
                'is_trending' => $product->is_trending,
            ];

            if ($standardVariant) {
                $standardVariant->update($variantData);
            } else {
                $product->variants()->create($variantData);
            }
        }

        // Clear relevant caches
        $this->cacheService->clearProductCaches($product->product_id, $product->category_id, $product->brand_id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully']);
        }

        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        try {
            // Check if product actually exists in database
            if (!$product->exists || !$product->product_id) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found - it may have already been deleted'
                    ], 404);
                }
                return redirect()->route('products.index')
                               ->with('error', 'Product not found - it may have already been deleted');
            }

            $productId = $product->product_id;
            $categoryId = $product->category_id;
            $brandId = $product->brand_id;

            $hasOrders = OrderItem::where(function($query) use ($product) {
                $query->where('itemable_type', Product::class)
                      ->where('itemable_id', $product->product_id)
                      ->orWhere(function($q) use ($product) {
                          $q->where('itemable_type', ProductVariant::class)
                            ->whereIn('itemable_id', $product->variants()->pluck('id'));
                      });
            })->exists();

            if ($hasOrders) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete product with existing orders'
                    ], 400);
                }
                return redirect()->back()
                               ->with('error', 'Cannot delete product with existing orders');
            }

            $product->variants()->delete();

            $rawImage = $product->getRawOriginal('image');
            if ($rawImage && Storage::disk('uploads')->exists($rawImage)) {
                Storage::disk('uploads')->delete($rawImage);
            }

            $product->delete();

            // ✅ CRITICAL: Clear cache AFTER deletion to prevent 404 errors on re-delete
            $this->cacheService->clearProductCaches($productId, $categoryId, $brandId);
            
            // Also clear related lists and stats
            \Illuminate\Support\Facades\Cache::forget('product_filter_stats');
            \Illuminate\Support\Facades\Cache::forget('products_total_count');
            \Illuminate\Support\Facades\Cache::forget('products_active_count');

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
            }

            return redirect()->route('products.index')
                             ->with('success', 'Product deleted successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Product not found - likely already deleted
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found - it may have already been deleted'
                ], 404);
            }
            return redirect()->route('products.index')
                           ->with('error', 'Product not found - it may have already been deleted');
        } catch (\Exception $e) {
            \Log::error('Product deletion failed', [
                'error' => $e->getMessage(),
                'product_id' => $product->product_id ?? 'unknown',
                'user_id' => auth()->id()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete product'
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to delete product');
        }
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $validated = $request->validate([
            'active' => 'required|boolean'
        ]);

        $product->update(['active' => $validated['active']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'active' => $product->active,
                'message' => 'Product status updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Product status updated successfully');
    }

    // Search functionality
    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = Product::where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->with('category', 'brand')
                          ->limit(10)
                          ->get(['product_id', 'name', 'mrp_price', 'stock_quantity']);

        return response()->json($products);
    }

    /**
     * Process and store a product image.
     * Logic: Standardizes uploads directory, generates unique names, and handles disk storage.
     */
    protected function processAndStoreImage($file, $folder = 'products')
    {
        if (!$file) return null;
        
        try {
            // Ensure folder exists
            Storage::disk('uploads')->makeDirectory($folder);
            
            // Generate filename: time_original.ext
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store file
            $path = $file->storeAs($folder, $filename, 'uploads');
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Image storage failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'name', 'category_id', 'brand_id', 'short_description', 'description', 
                'weight', 'weight_unit', 'length', 'width', 'height', 'dimension_unit', 
                'variant_value', 'variant_unit', 'variant_mrp', 'variant_stock', 'variant_sku'
            ]);
            fputcsv($file, [
                'Wax polish', '1', '1', 'Wax Polish enhances shine.', 'Wax Polish is a protective and polishing solution...', 
                '5.25', 'kg', '20.00', '14.00', '31.98', 'cm', 
                'Wox Polish', '5L', '2400.00', '50', 'A-WX-01'
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $fileHandle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($fileHandle); // Get headers

        // Clean headers (remove whitespace/BOM)
        $header = array_map(function($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($fileHandle)) !== false) {
                // Adjust row elements count to match header elements count (in case Excel added extra columns/commas)
                if (count($row) < count($header)) {
                    $row = array_pad($row, count($header), '');
                } elseif (count($row) > count($header)) {
                    $row = array_slice($row, 0, count($header));
                }

                $data = array_combine($header, $row);
                
                // Helper to find value by matching keys (exact or prefix match)
                $getVal = function($possibleKeys) use ($data) {
                    foreach ($possibleKeys as $key) {
                        if (isset($data[$key])) {
                            return trim($data[$key]);
                        }
                        // Prefix/partial matching
                        foreach ($data as $k => $v) {
                            if (str_starts_with($k, $key) || str_starts_with($key, $k)) {
                                return trim($v);
                            }
                        }
                    }
                    return null;
                };

                $name = $getVal(['name']);
                $categoryIdVal = $getVal(['category_id', 'category_i']);
                $brandIdVal = $getVal(['brand_id', 'brand_i']);

                $categoryId = $categoryIdVal !== null ? (int)$categoryIdVal : null;
                $brandId = $brandIdVal !== null ? (int)$brandIdVal : null;
                
                if (empty($name) || empty($categoryId) || empty($brandId)) {
                    $skipped++;
                    continue;
                }

                // 1. Get Category
                $category = Category::find($categoryId);

                // 2. Get Brand
                $brand = Brand::find($brandId);
                
                if (!$category || !$brand) {
                    $skipped++;
                    continue;
                }

                // Primary Variant fields
                $variantValue = $getVal(['variant_value', 'variant_va', 'variant_val']) ?? '';
                $variantUnitRaw = $getVal(['variant_unit', 'variant_un']) ?? '';

                // Normalize casing of unit (e.g. 500ML -> 500ml, 5l -> 5L) to match dropdown values
                $variantUnit = $variantUnitRaw;
                if (preg_match('/^(\d+)\s*(ml|ML)$/i', $variantUnitRaw, $matches)) {
                    $variantUnit = $matches[1] . 'ml';
                } elseif (preg_match('/^(\d+)\s*(l|L)$/i', $variantUnitRaw, $matches)) {
                    $variantUnit = $matches[1] . 'L';
                }
                $variantMrpVal = $getVal(['variant_mrp', 'variant_mr']) ?? '0';
                $variantStockVal = $getVal(['variant_stock', 'variant_st']) ?? '0';
                $variantSku = $getVal(['variant_sku']) ?? '';

                $variantMrp = (float)$variantMrpVal;
                $variantStock = (int)$variantStockVal;

                if (empty($variantSku)) {
                    $skipped++;
                    continue;
                }

                // 3. Create or Update Product
                $product = Product::updateOrCreate(
                    ['name' => $name],
                    [
                        'category_id' => $category->category_id,
                        'brand_id' => $brand->brand_id,
                        'short_description' => $getVal(['short_description', 'short_desc']) ?? '',
                        'description' => $getVal(['description', 'desc']) ?? '',
                        'weight' => (float)($getVal(['weight']) ?? 0.00),
                        'weight_unit' => $getVal(['weight_unit']) ?? 'kg',
                        'length' => (float)($getVal(['length']) ?? 0.00),
                        'width' => (float)($getVal(['width']) ?? 0.00),
                        'height' => (float)($getVal(['height']) ?? 0.00),
                        'dimension_unit' => $getVal(['dimension_unit', 'dimension']) ?? 'cm',
                        'active' => true,
                        'is_featured' => false,
                        'is_trending' => false,
                        'mrp_price' => $variantMrp,
                        'stock_quantity' => $variantStock,
                        'slug' => Str::slug($name),
                    ]
                );

                // 4. Create or Update the first Variant
                $variantName = ($variantValue && $variantUnit) ? ($variantValue . ' – ' . $variantUnit) : ($variantValue ?: ($variantUnit ?: 'Standard'));

                $variant = ProductVariant::updateOrCreate(
                    ['sku' => $variantSku],
                    [
                        'product_id' => $product->product_id,
                        'variant_name' => $variantName,
                        'variant_type' => 'flavour_volume',
                        'value' => $variantValue,
                        'variant_unit' => $variantUnit,
                        'mrp_price' => $variantMrp,
                        'stock_quantity' => $variantStock,
                        'active' => true,
                    ]
                );

                // 5. Create a complete Audit Snapshot (ProductRecord)
                $variants = $product->variants()->get(); // Get fresh variants
                \App\Models\ProductRecord::updateOrCreate(
                    ['sku' => $variantSku],
                    [
                        'product_name'      => $product->name,
                        'category_name'     => $category->category_name ?? 'N/A',
                        'brand_name'        => $brand->brand_name ?? 'N/A',
                        'product_full_data'  => $product->toArray(),
                        'category_full_data' => $category ? $category->toArray() : [],
                        'brand_full_data'    => $brand ? $brand->toArray() : [],
                        'variants_full_data' => $variants->toArray(),
                    ]
                );

                if ($product->wasRecentlyCreated) {
                    $inserted++;
                } else {
                    $updated++;
                }
            }

            fclose($fileHandle);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Bulk upload complete. Created: $inserted, Updated: $updated, Skipped: $skipped",
                'products' => Product::with(['category', 'brand'])->latest()->get()->values()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($fileHandle);
            \Log::error('Product Bulk Upload Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Product Bulk Upload Failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
