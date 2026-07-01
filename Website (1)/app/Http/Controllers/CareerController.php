<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'position'     => 'required|string|max:255',
            'resume'       => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
            'cover_letter' => 'nullable|string',
        ]);

        $data = [
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'] ?? null,
            'position'     => $validated['position'],
            'cover_letter' => $validated['cover_letter'] ?? null,
            'is_read'      => 0,
        ];

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $filename = Str::random(40) . '.' . $resume->getClientOriginalExtension();
            // Using move to public_path ensures it's placed in public/uploads/resumes
            $resume->move(public_path('uploads/resumes'), $filename);
            $data['resume_path'] = $filename;
        }

        JobApplication::create($data);

        return redirect()->back()->with('success', 'Your application has been submitted successfully. Our team will review it shortly!');
    }
}
