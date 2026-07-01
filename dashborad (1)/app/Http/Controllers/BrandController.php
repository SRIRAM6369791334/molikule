<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Services\BrandService;
use App\Services\AnalyticsService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    protected $brandService;
    protected $analyticsService;
    protected $cacheService;

    public function __construct(
        BrandService $brandService,
        AnalyticsService $analyticsService,
        CacheService $cacheService
    ) {
        $this->brandService = $brandService;
        $this->analyticsService = $analyticsService;
        $this->cacheService = $cacheService;
    }

    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where('brand_name', 'LIKE', "%{$searchTerm}%");
        }

        $brands = $query->orderBy('brand_id', 'desc')->get();
        return view('brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|max:255',
            'logo'       => 'required|image|mimes:jpeg,png,jpg,gif|max:5120|dimensions:width=1024,height=1024',
            'is_active'  => 'nullable|boolean',
            'is_featured'=> 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->processAndStoreImage($request->file('logo'), 'brands/logos');
        }

        // Set status accurately using modern Boolean parser
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        
        Brand::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully',
                'brands' => Brand::orderBy('brand_id', 'desc')->get()
            ]);
        }

        return redirect()->route('brands.index')->with('success', 'Brand created successfully!');
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $validated = $request->validate([
            'brand_name' => 'required|max:255',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120|dimensions:width=1024,height=1024',
            'is_active'  => 'nullable|boolean',
            'is_featured'=> 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $imagePath = $brand->getRawOriginal('logo');
            if ($imagePath && Storage::disk('uploads')->exists($imagePath)) {
                Storage::disk('uploads')->delete($imagePath);
            }
            $validated['logo'] = $this->processAndStoreImage($request->file('logo'), 'brands/logos');
        }

        // Handle boolean fields accurately
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $brand->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully',
                'brands' => Brand::orderBy('brand_id', 'desc')->get()
            ]);
        }

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        
        if ($brand->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete brand with existing products'
            ], 400);
        }

        $imagePath = $brand->getRawOriginal('logo');
        if ($imagePath && Storage::disk('uploads')->exists($imagePath)) {
            Storage::disk('uploads')->delete($imagePath);
        }

        $brand->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Brand deleted successfully',
            'brands' => Brand::orderBy('brand_id', 'desc')->get()
        ]);
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->is_active = !$brand->is_active;
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'is_active' => $brand->is_active
        ]);
    }

    public function getStats()
    {
        return response()->json($this->brandService->getBrandsWithStats());
    }

    public function getBrandsAjax(Request $request)
    {
        $brands = Brand::orderBy('brand_name')->get();
        return response()->json($brands);
    }

    private function processAndStoreImage($file, string $directory): string
    {
        $path = $file->store($directory, 'uploads');
        return $path;
    }
}
