<?php

namespace App\Http\Controllers;

use App\Models\NexusCertificationEnquiry;

class NexusCertificationController extends Controller
{
    public function index()
    {
        $enquiries = NexusCertificationEnquiry::orderBy('created_at', 'desc')->get();

        return view('nexus_certifications.index', compact('enquiries'));
    }

    public function markAsRead(NexusCertificationEnquiry $enquiry)
    {
        $enquiry->update(['is_read' => true]);

        return back()->with('success', 'NEXUS enquiry marked as read.');
    }

    public function markAsUnread(NexusCertificationEnquiry $enquiry)
    {
        $enquiry->update(['is_read' => false]);

        return back()->with('success', 'NEXUS enquiry marked as unread.');
    }

    public function destroy(NexusCertificationEnquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('nexus-certifications.index')->with('success', 'NEXUS enquiry deleted successfully.');
    }
}
