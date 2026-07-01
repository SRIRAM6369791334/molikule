<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    public function index()
    {
        $positions = JobPosition::orderBy('title')->get();
        return view('job_positions.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255|unique:job_positions,title',
            'is_active' => 'nullable|boolean',
        ]);

        JobPosition::create([
            'title'     => $validated['title'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Job Position created successfully.');
    }

    public function update(Request $request, JobPosition $jobPosition)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255|unique:job_positions,title,' . $jobPosition->id,
            'is_active' => 'nullable|boolean',
        ]);

        $jobPosition->update([
            'title'     => $validated['title'],
            'is_active' => $request->boolean('is_active', $jobPosition->is_active),
        ]);

        return back()->with('success', 'Job Position updated successfully.');
    }

    public function destroy(JobPosition $jobPosition)
    {
        $jobPosition->delete();
        return back()->with('success', 'Job Position deleted successfully.');
    }

    public function toggleStatus(JobPosition $jobPosition)
    {
        $jobPosition->update(['is_active' => !$jobPosition->is_active]);
        return response()->json([
            'success'   => true,
            'is_active' => $jobPosition->is_active,
            'message'   => 'Status updated.'
        ]);
    }
}
