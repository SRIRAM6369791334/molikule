<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the contact messages.
     */
    public function index()
    {
        // Load messages, ordered by newest first
        $messages = Contact::orderBy('created_at', 'desc')->get();
        return view('contacts.index', compact('messages'));
    }

    /**
     * Mark a message as read or toggle its status.
     */
    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Mark a message as unread.
     */
    public function markAsUnread(Contact $contact)
    {
        $contact->update(['is_read' => false]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message marked as unread.');
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message deleted successfully.');
    }
}
