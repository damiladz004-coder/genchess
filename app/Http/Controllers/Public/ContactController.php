<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReceived;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'service' => 'nullable|in:school,community,home,instructor,products,general',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        $supportEmail = Setting::where('key', 'support_email')->value('value');
        $recipient = $supportEmail ?: config('mail.from.address');

        try {
            Mail::to($recipient)->send(new ContactMessageReceived($data));
        } catch (\Throwable $e) {
            Log::error('Contact form email failed.', [
                'error' => $e->getMessage(),
                'email' => $data['email'],
            ]);

            return back()
                ->withInput()
                ->withErrors(['contact' => 'We could not send your message right now. Please try again shortly.']);
        }

        return back()->with('success', 'Your message has been sent. We will get back to you soon.');
    }
}
