<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\VariantAttribute;
use App\Models\VariantAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product.brand', 'product.category']);

        // 1. Brand Filter
        if ($request->filled('brand_id')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

        // 2. Category Filter
        if ($request->filled('category_id')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // 3. Product Filter
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // 4. Global Search (Across multiple entities)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('variant_name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhereHas('product', function($productQ) use ($search) {
                      $productQ->where('name', 'like', '%' . $search . '%')
                              ->orWhere('sku', 'like', '%' . $search . '%')
                              ->orWhereHas('brand', function($brandQ) use ($search) {
                                  $brandQ->where('brand_name', 'like', '%' . $search . '%');
                              })
                              ->orWhereHas('category', function($catQ) use ($search) {
                                  $catQ->where('category_name', 'like', '%' . $search . '%');
                              });
                  });
            });
        }

        $variants = $query->latest()->paginate(50)->appends($request->query());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($variants);
        }

        $products = Product::active()->orderBy('name')->get();
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::orderBy('brand_name')->get();

        $existingNames = ProductVariant::whereNotNull('variant_name')->distinct()->pluck('variant_name');

        // Get some stats 
        $totalVariants = ProductVariant::count();
        $activeVariants = ProductVariant::active()->count();

        return view('product-variants', compact('variants', 'products', 'categories', 'brands', 'totalVariants', 'activeVariants', 'existingNames'));
    }

    public function create(?Product $product = null)
    {
        $products = Product::active()->orderBy('name')->get();
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::orderBy('brand_name')->get();
        
        $existingNames = ProductVariant::whereNotNull('variant_name')->distinct()->pluck('variant_name');
        $existingTypes = ProductVariant::whereNotNull('variant_type')->distinct()->pluck('variant_type');
        
        return view('product-variants.create', compact('products', 'product', 'categories', 'brands', 'existingNames', 'existingTypes'));
    }

    public function store(StoreProductVariantRequest $request)
    {
        $validated = $request->validated();

        // Set booleans
        $validated['active'] = $request->boolean('active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');

        // Handle image upload if present
        if ($request->hasFile('variant_image')) {
            $validated['variant_image'] = $this->processAndStoreImage($request->file('variant_image'), 'variants/images');
        }

        $validated['discount_value'] = $request->input('discount_value', 0) ?: 0;

        ProductVariant::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Variant created successfully']);
        }

        return redirect()->route('product-variants.index')
                         ->with('success', 'Variant created successfully!');
    }

    public function show(ProductVariant $product_variant)
    {
        $variant = $product_variant;
        $variant->load('product');

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($variant);
        }

        return view('product-variants.show', compact('variant'));
    }

    public function edit(ProductVariant $product_variant)
    {
        $variant = $product_variant;
        $variant->load('product');
        $product = $variant->product;
        $products = Product::where('active', 1)->orderBy('name')->get();
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::orderBy('brand_name')->get();
        
        $existingNames = ProductVariant::whereNotNull('variant_name')->distinct()->pluck('variant_name');
        $existingTypes = ProductVariant::whereNotNull('variant_type')->distinct()->pluck('variant_type');
        
        return view('product-variants.edit', compact('variant', 'product', 'products', 'categories', 'brands', 'existingNames', 'existingTypes'));
    }

    public function update(UpdateProductVariantRequest $request, ProductVariant $product_variant)
    {
        $variant = $product_variant;
        $validated = $request->validated();

        // Handle image upload if present
        if ($request->hasFile('variant_image')) {
            // Delete old image
            if ($variant->variant_image && Storage::disk('uploads')->exists($variant->variant_image)) {
                Storage::disk('uploads')->delete($variant->variant_image);
            }
            $validated['variant_image'] = $this->processAndStoreImage($request->file('variant_image'), 'variants/images');
        }

        $validated['active'] = $request->boolean('active', $variant->active);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['discount_value'] = $request->input('discount_value', 0) ?: 0;

        $variant->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Variant updated successfully']);
        }

        return redirect()->route('product-variants.index')
                         ->with('success', 'Variant updated successfully!');
    }

    public function destroy(ProductVariant $product_variant)
    {
        $variant = $product_variant;
        // Delete variant image if exists
        if ($variant->variant_image && Storage::disk('uploads')->exists($variant->variant_image)) {
            Storage::disk('uploads')->delete($variant->variant_image);
        }

        $variant->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Variant deleted successfully']);
        }

        return redirect()->route('product-variants.index')
                         ->with('success', 'Variant deleted successfully!');
    }

    // Product-specific variant management
    public function productVariants(Product $product)
    {
        $variants = $product->variants()->get();
        return view('products.variants.index', compact('product', 'variants'));
    }

    public function createForProduct(Product $product)
    {
        $existingNames = ProductVariant::whereNotNull('variant_name')->distinct()->pluck('variant_name');
        $existingTypes = ProductVariant::whereNotNull('variant_type')->distinct()->pluck('variant_type');
        
        return view('products.variants.create', compact('product', 'existingNames', 'existingTypes'));
    }

    public function storeForProduct(StoreProductVariantRequest $request, Product $product)
    {
        $validated = $request->validated();

        $validated['product_id'] = $product->product_id;
        $validated['active'] = $request->boolean('active', true);

        // Handle image upload if present
        if ($request->hasFile('variant_image')) {
            $validated['variant_image'] = $this->processAndStoreImage($request->file('variant_image'), 'variants/images');
        }

        // Set default values for nullable fields that might cause database issues
        if (!isset($validated['active']) || $validated['active'] === null) {
            $validated['active'] = true;
        }

        ProductVariant::create($validated);

        return redirect()->route('products.variants.index', $product)
                         ->with('success', 'Variant created successfully!');
    }

    /**
     * Bulk update variants (activate, deactivate, delete)
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $variantIds = $validated['ids'];
        $action = $validated['action'];

        $variants = ProductVariant::whereIn('id', $variantIds)->get();

        if ($variants->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No variants found with the provided IDs'
            ]);
        }

        $count = 0;
        switch ($action) {
            case 'activate':
                $count = ProductVariant::whereIn('id', $variantIds)->update(['active' => true]);
                $message = "Successfully activated {$count} variant(s)";
                break;
 
            case 'deactivate':
                $count = ProductVariant::whereIn('id', $variantIds)->update(['active' => false]);
                $message = "Successfully deactivated {$count} variant(s)";
                break;

            case 'delete':
                $count = ProductVariant::whereIn('id', $variantIds)->delete();
                $message = "Successfully deleted {$count} variant(s)";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count
        ]);
    }

    /**
     * Toggle variant active status
     */
    public function toggleStatus(Request $request, ProductVariant $product_variant)
    {
        $variant = $product_variant;
        $validated = $request->validate([
            'active' => 'required|boolean'
        ]);

        $variant->update(['active' => $validated['active']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Variant status updated successfully',
                'active' => $variant->active
            ]);
        }

        return redirect()->back()->with('success', 'Variant status updated successfully');
    }

    /**
     * Show bulk variant creation form
     */
    public function bulkCreate()
    {
        $products = Product::active()->orderBy('name')->get();
        $attributes = VariantAttribute::with('activeValues')->active()->ordered()->get();
        
        $existingNames = ProductVariant::whereNotNull('variant_name')->distinct()->pluck('variant_name');
        $existingTypes = ProductVariant::whereNotNull('variant_type')->distinct()->pluck('variant_type');

        return view('product-variants.bulk-create', compact('products', 'attributes', 'existingNames', 'existingTypes'));
    }

    /**
     * Store bulk created variants
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'attributes' => 'required|array|min:1',
            'attributes.*' => 'exists:variant_attributes,id',
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'array|min:1',
            'default_stock' => 'nullable|integer|min:0',
            'default_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($validated['product_id']);
            $attributeIds = $validated['attributes'];
            $attributeValues = $validated['attribute_values'];
            $defaultStock = $validated['default_stock'] ?? 100;
            $isActive = $request->boolean('default_active', true);

            // Build combinations
            $combinations = $this->generateCombinations($attributeIds, $attributeValues);

            if (empty($combinations)) {
                return redirect()->back()
                    ->with('error', 'No valid combinations found. Please select at least one value for each attribute.');
            }

            $createdCount = 0;
            $errors = [];

            foreach ($combinations as $combination) {
                try {
                    // Calculate price with modifiers
                    $price = $this->calculateVariantPrice($product->mrp_price, $combination);

                    // Generate variant name
                    $variantName = $this->generateVariantName($combination);

                    // [LOGIC] Check if variant with these attributes already exists for this product
                    $existingVariantId = DB::table('product_variants as pv')
                        ->join('product_variant_attribute_values as pvav', 'pv.id', '=', 'pvav.variant_id')
                        ->where('pv.product_id', $product->product_id)
                        ->whereIn('pvav.value_id', array_values($combination))
                        ->groupBy('pv.id')
                        ->having(DB::raw('count(pvav.value_id)'), '=', count($combination))
                        ->value('pv.id');

                    if ($existingVariantId) {
                        $errors[] = "Variant already exists: {$variantName}";
                        continue;
                    }

                    // Create variant
                    $variant = ProductVariant::create([
                        'product_id' => $product->product_id,
                        'variant_name' => $variantName,
                        'mrp_price' => $price,
                        'stock_quantity' => $defaultStock,
                        'active' => $isActive,
                        'variant_value' => implode('-', array_values($combination)), // Store combination ID key
                        'value' => $variantName, // Fallback for legacy code
                    ]);

                    // Link attribute values to variant
                    foreach ($combination as $attributeId => $valueId) {
                        DB::table('product_variant_attribute_values')->insert([
                            'variant_id' => $variant->id,
                            'attribute_id' => $attributeId,
                            'value_id' => $valueId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $createdCount++;

                } catch (\Exception $e) {
                    $errors[] = "Failed to create variant: {$variantName} - " . $e->getMessage();
                }
            }

            DB::commit();

            if ($createdCount > 0) {
                $message = "Successfully created {$createdCount} variant(s)!";
                if (!empty($errors)) {
                    $message .= " " . count($errors) . " variant(s) failed.";
                }
                return redirect()->route('product-variants.index')
                    ->with('success', $message);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to create variants. ' . implode(' ', $errors));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Generate all combinations from selected attributes and values
     */
    private function generateCombinations($attributeIds, $attributeValues)
    {
        $combinations = [[]];

        foreach ($attributeIds as $attributeId) {
            if (!isset($attributeValues[$attributeId]) || empty($attributeValues[$attributeId])) {
                continue;
            }

            $newCombinations = [];
            foreach ($attributeValues[$attributeId] as $valueId) {
                foreach ($combinations as $combination) {
                    $newCombinations[] = array_merge($combination, [$attributeId => $valueId]);
                }
            }
            $combinations = $newCombinations;
        }

        return $combinations;
    }

    /**
     * Calculate variant price with attribute modifiers
     */
    private function calculateVariantPrice($basePrice, $combination)
    {
        $totalPrice = $basePrice;

        foreach ($combination as $attributeId => $valueId) {
            $value = VariantAttributeValue::find($valueId);
            if ($value && $value->price_modifier != 0) {
                if ($value->price_modifier_type === 'percentage') {
                    $totalPrice += ($basePrice * ($value->price_modifier / 100));
                } else {
                    $totalPrice += $value->price_modifier;
                }
            }
        }

        return round($totalPrice, 2);
    }

    /**
     * Generate variant name from combination
     */
    private function generateVariantName($combination)
    {
        $parts = [];

        foreach ($combination as $attributeId => $valueId) {
            $value = VariantAttributeValue::find($valueId);
            if ($value) {
                $parts[] = $value->value;
            }
        }

        return implode(' / ', $parts);
    }

    /**
     * AJAX: Get categories that have products belonging to a specific brand
     */
    public function getCategoriesByBrandAjax(Request $request)
    {
        $brandId = $request->query('brand_id');
        
        if (!$brandId) {
            return response()->json(\App\Models\Category::orderBy('category_name')->get());
        }

        $categories = \App\Models\Category::whereHas('products', function($q) use ($brandId) {
            $q->where('brand_id', $brandId);
        })->orderBy('category_name')->get();

        return response()->json($categories);
    }

    public function getProductsAjax(Request $request)
    {
        $categoryId = $request->query('category_id');
        $brandId = $request->query('brand_id');

        $query = Product::active();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        $products = $query->orderBy('name', 'asc')->get(['product_id', 'name']);
        return response()->json($products);
    }

    /**
     * AJAX: Get single variant for editing
     */
    public function ajaxEdit(ProductVariant $product_variant)
    {
        return response()->json($product_variant->load('product'));
    }

    /**
     * AJAX: Store new variant (Kumarimall style)
     */
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required',
            'mrp_price' => 'required|numeric',
            'offer_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'low_stock' => 'nullable|integer',
            'variant_image' => 'nullable|image|max:5120',
            'unit_id' => 'required',
            'value' => 'required',
            'variant_name' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['active'] = true;
        $data['compare_price'] = $request->offer_price; // Map offer_price to compare_price used in Molikule
        
        if ($request->hasFile('variant_image')) {
            $data['variant_image'] = $this->processAndStoreImage($request->file('variant_image'), 'variants/images');
        }

        $variant = ProductVariant::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Variant created successfully',
            'variant' => $variant->load('product'),
            'variants' => ProductVariant::with('product')->latest()->take(50)->get()
        ]);
    }

    /**
     * AJAX: Update variant
     */
    public function ajaxUpdate(Request $request, ProductVariant $product_variant)
    {
        $variant = $product_variant;
        $data = $request->all();

        if ($request->hasFile('variant_image')) {
            if ($variant->variant_image && Storage::disk('uploads')->exists($variant->variant_image)) {
                Storage::disk('uploads')->delete($variant->variant_image);
            }
            $data['variant_image'] = $this->processAndStoreImage($request->file('variant_image'), 'variants/images');
        }

        if ($request->has('offer_price')) {
            $data['compare_price'] = $request->offer_price;
        }

        if ($request->has('active')) {
            $data['active'] = $request->active == '1' || $request->active == 'on' || $request->active === true;
        }

        $variant->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Variant updated successfully',
            'variant' => $variant->load('product'),
            'variants' => ProductVariant::with('product')->latest()->take(50)->get()
        ]);
    }

    /**
     * AJAX: Destroy variant
     */
    public function ajaxDestroy(ProductVariant $product_variant)
    {
        if ($product_variant->variant_image && Storage::disk('uploads')->exists($product_variant->variant_image)) {
            Storage::disk('uploads')->delete($product_variant->variant_image);
        }
        $product_variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully'
        ]);
    }
    /**
     * Store image using simple Laravel file storage
     */
    private function processAndStoreImage($file, string $directory): string
    {
        // Simple file storage without image processing
        $path = $file->store($directory, 'uploads');
        return $path;
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="variants_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'product_id', 'variant_value', 'variant_unit', 'variant_mrp', 'variant_stock', 'variant_sku'
            ]);
            fputcsv($file, [
                '1', 'Wox Polish Scent', '5L', '2400.00', '50', 'A-WX-01'
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

        // Clean headers (remove whitespace/BOM/lowercasing)
        $header = array_map(function($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $skipReasons = [];

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

                $productIdVal = $getVal(['product_id', 'product_i']);
                $productId = $productIdVal !== null ? (int)$productIdVal : null;

                if (empty($productId)) {
                    $skipped++;
                    $skipReasons[] = "Row " . ($inserted + $updated + $skipped) . ": product_id is empty";
                    continue;
                }

                // Verify product exists
                $product = Product::find($productId);
                if (!$product) {
                    $skipped++;
                    $skipReasons[] = "Row " . ($inserted + $updated + $skipped) . ": Product ID {$productId} not found in database";
                    continue;
                }

                $variantValue = $getVal(['variant_value', 'variant_va', 'variant_val']) ?? '';
                $variantUnitRaw = $getVal(['variant_unit', 'variant_un']) ?? '';

                // Normalize casing of unit (e.g. 500ML -> 500ml, 5l -> 5L) to match dropdown values
                $variantUnit = $variantUnitRaw;
                if (preg_match('/^(\d+)\s*(ml|ML)$/i', $variantUnitRaw, $matches)) {
                    $variantUnit = $matches[1] . 'ml';
                } elseif (preg_match('/^(\d+)\s*(l|L)$/i', $variantUnitRaw, $matches)) {
                    $variantUnit = $matches[1] . 'L';
                }

                $variantSku = $getVal(['variant_sku', 'sku']) ?? '';
                if (empty($variantSku)) {
                    // Auto-generate unique SKU based on product, flavor/value, and unit
                    $valuePart = !empty($variantValue) ? preg_replace('/[^A-Za-z0-9]/', '', $variantValue) : 'STANDARD';
                    $unitPart = !empty($variantUnit) ? preg_replace('/[^A-Za-z0-9]/', '', $variantUnit) : 'UNIT';
                    $variantSku = 'MOL-' . $productId . '-' . strtoupper($valuePart) . '-' . strtoupper($unitPart);
                }
                $variantMrpVal = $getVal(['variant_mrp', 'variant_mr']) ?? '0';
                $variantStockVal = $getVal(['variant_stock', 'variant_st']) ?? '0';

                $variantMrp = (float)$variantMrpVal;
                $variantStock = (int)$variantStockVal;

                $variantName = ($variantValue && $variantUnit) ? ($variantValue . ' – ' . $variantUnit) : ($variantValue ?: ($variantUnit ?: 'Standard'));

                // Create or Update Product Variant
                $variant = ProductVariant::updateOrCreate(
                    ['sku' => $variantSku],
                    [
                        'product_id' => $productId,
                        'variant_name' => $variantName,
                        'variant_type' => 'flavour_volume',
                        'value' => $variantValue,
                        'variant_unit' => $variantUnit,
                        'mrp_price' => $variantMrp,
                        'stock_quantity' => $variantStock,
                        'active' => true,
                    ]
                );

                // Update product main price and stock to keep in sync
                $minPrice = ProductVariant::where('product_id', $productId)->min('mrp_price') ?? $variantMrp;
                $totalStock = ProductVariant::where('product_id', $productId)->sum('stock_quantity') ?? $variantStock;
                
                $product->update([
                    'mrp_price' => $minPrice,
                    'stock_quantity' => $totalStock,
                ]);

                // Create audit snapshot (ProductRecord) if possible
                try {
                    $category = $product->category;
                    $brand = $product->brand;
                    $variants = $product->variants()->get();
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
                } catch (\Exception $ex) {
                    // Ignore audit failures
                }

                if ($variant->wasRecentlyCreated) {
                    $inserted++;
                } else {
                    $updated++;
                }
            }

            fclose($fileHandle);
            DB::commit();

            $msg = "Bulk upload complete. Created: $inserted, Updated: $updated, Skipped: $skipped";
            if (!empty($skipReasons)) {
                $msg .= ". Skip Reasons: " . implode(" | ", array_slice($skipReasons, 0, 3));
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
                'skip_reasons' => $skipReasons,
                'variants' => ProductVariant::with(['product.category', 'product.brand'])->latest()->take(100)->get()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($fileHandle);
            \Log::error('Variant Bulk Upload Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Variant Bulk Upload Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadImageAjax(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $variant = ProductVariant::findOrFail($id);
        
        if ($request->hasFile('image')) {
            $path = $this->processAndStoreImage($request->file('image'), 'variants/images');
            if ($path) {
                $variant->update(['variant_image' => $path]);
                return response()->json([
                    'success' => true,
                    'message' => 'Variant image uploaded successfully',
                    'image_url' => asset('uploads/' . $path),
                    'variants' => ProductVariant::with(['product.category', 'product.brand'])->latest()->take(100)->get()
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Failed to store image'], 400);
    }
}
