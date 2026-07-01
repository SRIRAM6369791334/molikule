<?php

namespace App\Http\Controllers;

use App\Models\Pincode;
use App\Services\PincodeService;
use Illuminate\Http\Request;

class PincodeController extends Controller
{
    protected $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->pincodeService = $pincodeService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction', 'asc')
        ];

        $pincodes = $this->pincodeService->getFilteredPincodes($filters, 50);
        $stats = $this->pincodeService->getPincodesWithStats();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'pincodes' => $pincodes,
                'stats' => $stats
            ]);
        }

        return view('pincodes.index', compact('pincodes', 'stats'));
    }

    public function create()
    {
        return view('pincodes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pincode' => 'required|string|max:10|unique:pincodes,pincode',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'cod_charge' => 'nullable|numeric|min:0|max:999999.99',
            'priority' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        Pincode::create([
            'pincode' => $validated['pincode'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'] ?? 'India',
            'cod_charge' => $validated['cod_charge'] ?? 120.00,
            'priority' => $validated['priority'] ?? 0,
            'is_active' => $validated['is_active'] ?? true
        ]);

        return redirect()->route('pincodes.index')
                         ->with('success', 'Pincode created successfully!');
    }

    public function show(Pincode $pincode)
    {
        return view('pincodes.show', compact('pincode'));
    }

    public function edit(Pincode $pincode)
    {
        return view('pincodes.edit', compact('pincode'));
    }

    public function update(Request $request, Pincode $pincode)
    {
        $validated = $request->validate([
            'pincode' => 'required|string|max:10|unique:pincodes,pincode,' . $pincode->id,
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'cod_charge' => 'nullable|numeric|min:0|max:999999.99',
            'priority' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $pincode->update([
            'pincode' => $validated['pincode'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'] ?? 'India',
            'cod_charge' => $validated['cod_charge'] ?? 120.00,
            'priority' => $validated['priority'] ?? 0,
            'is_active' => $validated['is_active'] ?? false
        ]);

        return redirect()->route('pincodes.index')
                         ->with('success', 'Pincode updated successfully!');
    }

    public function destroy(Pincode $pincode)
    {
        $pincode->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pincode deleted successfully']);
        }

        return redirect()->route('pincodes.index')
                         ->with('success', 'Pincode deleted successfully!');
    }

    /**
     * Toggle pincode active status
     */
    public function toggleStatus(Request $request, Pincode $pincode)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $pincode->update(['is_active' => $validated['is_active']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pincode status updated successfully',
                'is_active' => $pincode->is_active
            ]);
        }

        return redirect()->back()->with('success', 'Pincode status updated successfully');
    }

    /**
     * Bulk update pincodes (activate, deactivate, delete)
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $pincodeIds = $validated['ids'];
        $action = $validated['action'];

        $pincodes = Pincode::whereIn('id', $pincodeIds)->get();

        if ($pincodes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No pincodes found with the provided IDs'
            ]);
        }

        $count = 0;
        $skipped = 0;

        switch ($action) {
            case 'activate':
                $count = $this->pincodeService->bulkActivatePincodes($pincodeIds);
                $message = "Successfully activated {$count} pincode(s)";
                break;

            case 'deactivate':
                $count = $this->pincodeService->bulkDeactivatePincodes($pincodeIds);
                $message = "Successfully deactivated {$count} pincode(s)";
                break;

            case 'delete':
                $count = $this->pincodeService->bulkDeletePincodes($pincodeIds);
                $message = "Successfully deleted {$count} pincode(s)";
                break;
        }

        return response()->json([
            'success' => $count > 0,
            'message' => $message,
            'count' => $count,
            'skipped' => $skipped ?? 0
        ]);
    }

    /**
     * Get pincodes for AJAX select/dropdown
     */
    public function getPincodesAjax(Request $request)
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10);

        $pincodes = $this->pincodeService->searchPincodes($search, $limit);

        return response()->json([
            'results' => $pincodes->map(function($pincode) {
                return [
                    'id' => $pincode->id,
                    'text' => $pincode->formatted_location,
                    'pincode' => $pincode->pincode,
                    'city' => $pincode->city,
                    'state' => $pincode->state
                ];
            })
        ]);
    }

    /**
     * Get pincode statistics for dashboard/widgets
     */
    public function getStats()
    {
        $stats = $this->pincodeService->getPincodesWithStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Validate pincode for delivery
     */
    public function validateForDelivery(Request $request)
    {
        $validated = $request->validate([
            'pincode' => 'required|string|max:10'
        ]);

        $result = $this->pincodeService->validatePincodeForDelivery($validated['pincode']);

        return response()->json($result);
    }
}
