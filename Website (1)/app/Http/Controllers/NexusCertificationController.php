<?php

namespace App\Http\Controllers;

use App\Models\NexusCertificationEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class NexusCertificationController extends Controller
{
    private const SEGMENTS = [
        'Laundry Care',
        'Housekeeping',
        'Kitchen Hygiene',
        'Healthcare',
        'Auto Care',
        'Industrial Cleaning',
        'Facility Management',
        'Other',
    ];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'segment' => ['required', 'string', Rule::in(self::SEGMENTS)],
            'thoughts' => 'required|string|max:2000',
        ]);

        try {
            $enquiry = NexusCertificationEnquiry::create($validated);

            try {
                $adminEmail = env('ADMIN_EMAIL');

                if ($adminEmail) {
                    Mail::send('emails.nexus-certification-enquiry', ['enquiry' => $enquiry], function ($message) use ($adminEmail) {
                        $message->to($adminEmail);
                        $message->subject('New NEXUS Certification Enquiry');
                    });
                }
            } catch (\Exception $e) {
                Log::error('NEXUS certification enquiry email failed: ' . $e->getMessage());
            }

            return back()->with('success', 'Your NEXUS certification enquiry has been submitted successfully.');
        } catch (\Exception $e) {
            Log::error('NEXUS Certification Submission Error: ' . $e->getMessage());

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
