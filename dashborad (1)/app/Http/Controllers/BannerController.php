<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\BannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBannerRequest;

class BannerController extends Controller
{
    protected $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'asc')
        ];

        $banners = $this->bannerService->getFilteredBanners($filters);
        $stats = $this->bannerService->getBannersWithStats();

        return view('banners.index', compact('banners', 'stats'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(StoreBannerRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload - Required
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('banners', 'uploads');
        }

        

        // Set defaults
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['position'] = $validated['position'] ?? 'homepage'; // Set default position
        $validated['button_text'] = $validated['button_text'] ?? 'Read More'; // Set default button text
        $validated['banner_type'] = $validated['banner_type'] ?? 'static'; // Set default banner type

        $banner = Banner::create($validated);

        return redirect()->route('banners.index')
                         ->with('success', 'Banner created successfully!');
    }

    public function show(Banner $banner)
    {
        return view('banners.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:50|unique:banners,title,' . $banner->id . ',id',
            'subtitle' => 'nullable|string|max:150',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:255',
            'position' => 'nullable|string', // Position is now hidden, default will be set in controller
            'button_text' => 'nullable|string|max:255', // Button text is now hidden, default will be set in controller
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
           
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_url && Storage::disk('uploads')->exists($banner->image_url)) {
                Storage::disk('uploads')->delete($banner->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('banners', 'uploads');
        }

        
        // Set defaults
        $validated['is_active'] = $request->boolean('is_active', $banner->is_active);
        $validated['sort_order'] = $validated['sort_order'] ?? $banner->sort_order;
        $validated['position'] = $validated['position'] ?? $banner->position; // Retain existing position if not provided
        $validated['button_text'] = $validated['button_text'] ?? $banner->button_text; // Retain existing button text if not provided

        $banner->update($validated);

        return redirect()->route('banners.index')
                         ->with('success', 'Banner updated successfully!');
    }

    public function destroy(Banner $banner)
    {
        // Delete image if exists
        if ($banner->image_url && Storage::disk('uploads')->exists($banner->image_url)) {
            Storage::disk('uploads')->delete($banner->image_url);
        }
      

        $banner->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully']);
        }

        return redirect()->route('banners.index')
                         ->with('success', 'Banner deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $banners = Banner::where('title', 'like', "%{$query}%")
                        ->take(10)
                        ->get(['id', 'title']);

        return response()->json($banners);
    }

    // AJAX methods for data tables
    public function ajaxIndex(Request $request)
    {
        $query = Banner::query();

        // Apply search filter
        if ($search = $request->get('search')['value'] ?? null) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Apply ordering
        $banners = Banner::orderBy('sort_order', 'asc')->get();
        return response()->json($banners);
    }

    /**
     * Toggle banner active status
     */
    public function toggleStatus(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $banner->update(['is_active' => $validated['is_active']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Banner status updated successfully',
                'is_active' => $banner->is_active
            ]);
        }

        return redirect()->back()->with('success', 'Banner status updated successfully');
    }

    /**
     * Bulk update banners (activate, deactivate, delete)
     */
    public function bulkUpdate(Request $request)
    {
        return $this->handleBulkUpdate($request, Banner::class, $this->bannerService, 'id');
    }

    /**
     * Get banners for AJAX select/dropdown
     */
    public function getBannersAjax(Request $request)
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10);

        $banners = $this->bannerService->searchBanners($search, $limit);

        return response()->json([
            'results' => $banners->map(function($banner) {
                return [
                    'id' => $banner->id,
                    'text' => $banner->title
                ];
            })
        ]);
    }

    /**
     * Get banner statistics for dashboard/widgets
     */
    public function getStats()
    {
        $stats = $this->bannerService->getBannersWithStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

}
