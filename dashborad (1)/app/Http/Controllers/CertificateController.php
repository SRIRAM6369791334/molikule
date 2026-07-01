<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    // ── List ─────────────────────────────────────────────────
    public function index()
    {
        $certificates = Certificate::ordered()->get();
        return view('certificates.index', compact('certificates'));
    }

    // ── Create form ──────────────────────────────────────────
    public function create()
    {
        return view('certificates.create');
    }

    // ── Store ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:200',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data = [
            'title'      => $validated['title'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')
                                          ->store('certificates', 'uploads');
        }

        Certificate::create($data);

        return redirect()->route('certificates.index')
                         ->with('success', 'Certificate added successfully!');
    }

    // ── Edit form ─────────────────────────────────────────────
    public function edit(Certificate $certificate)
    {
        return view('certificates.edit', compact('certificate'));
    }

    // ── Update ────────────────────────────────────────────────
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:200',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data = [
            'title'      => $validated['title'],
            'sort_order' => $validated['sort_order'] ?? $certificate->sort_order,
            'is_active'  => $request->boolean('is_active', $certificate->is_active),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($certificate->image_path &&
                Storage::disk('uploads')->exists($certificate->image_path)) {
                Storage::disk('uploads')->delete($certificate->image_path);
            }
            $data['image_path'] = $request->file('image')
                                          ->store('certificates', 'uploads');
        }

        $certificate->update($data);

        return redirect()->route('certificates.index')
                         ->with('success', 'Certificate updated successfully!');
    }

    // ── Delete ────────────────────────────────────────────────
    public function destroy(Certificate $certificate)
    {
        if ($certificate->image_path &&
            Storage::disk('uploads')->exists($certificate->image_path)) {
            Storage::disk('uploads')->delete($certificate->image_path);
        }

        $certificate->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Certificate deleted.']);
        }

        return redirect()->route('certificates.index')
                         ->with('success', 'Certificate deleted successfully!');
    }

    // ── Toggle active status (AJAX) ───────────────────────────
    public function toggleStatus(Request $request, Certificate $certificate)
    {
        $certificate->update(['is_active' => !$certificate->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $certificate->is_active,
            'message'   => 'Status updated.',
        ]);
    }
}
