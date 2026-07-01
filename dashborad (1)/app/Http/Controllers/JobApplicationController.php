<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::orderBy('created_at', 'desc')->get();
        return view('job_applications.index', compact('applications'));
    }

    public function show(JobApplication $jobApplication)
    {
        if (!$jobApplication->is_read) {
            $jobApplication->update(['is_read' => true]);
        }
        return view('job_applications.show', compact('jobApplication'));
    }

    public function markAsRead(JobApplication $jobApplication)
    {
        $jobApplication->update(['is_read' => true]);
        return back()->with('success', 'Application marked as read.');
    }

    public function markAsUnread(JobApplication $jobApplication)
    {
        $jobApplication->update(['is_read' => false]);
        return back()->with('success', 'Application marked as unread.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        // Delete the file if it exists
        $filePath = public_path('uploads/resumes/' . $jobApplication->resume_path);
        if (file_exists($filePath) && !is_dir($filePath)) {
            unlink($filePath);
        }

        $jobApplication->delete();
        return redirect()->route('job-applications.index')->with('success', 'Application deleted successfully.');
    }
}
