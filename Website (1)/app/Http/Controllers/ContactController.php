<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContactController extends Controller
{

public function store(Request $request)
{
    // ✅ Validation (strict + production-safe)
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:255',
        'email'    => 'required|email|max:255',
        'phone'    => 'required|digits_between:10,15',
        'subject'  => 'required|string|max:255',
        'message'  => 'nullable|string|max:2000',
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        \App\Models\Contact::create([
            'username' => $request->username,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'subject'  => $request->subject,
            'message'  => $request->message,
        ]);

        // Send Email to Admin
        try {
            $adminEmail = env('ADMIN_EMAIL');
            if ($adminEmail) {
                $contactData = [
                    'name' => $request->username,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'subject' => $request->subject,
                    'message' => $request->message
                ];
                Mail::send('emails.contact-form', ['data' => $contactData], function($message) use ($adminEmail, $request) {
                    $message->to($adminEmail);
                    $message->subject('New Contact Inquiry: ' . ($request->subject ?? 'Molikule Website'));
                });
            }
        } catch (\Exception $e) {
            Log::error('Contact email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Message sent successfully');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Contact Submission Error: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong. Please try again.');
    }
}

}
