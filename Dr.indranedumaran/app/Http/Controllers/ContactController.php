<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot + minimum fill time (bots tend to submit instantly)
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('sent', true);
        }
        try {
            $started = (int) decrypt($request->input('_ft'));
        } catch (\Throwable) {
            $started = 0;
        }
        if ($started === 0 || time() - $started < 4 || time() - $started > 7200) {
            return back()->withInput()->withErrors(['message' => 'Please wait a moment and submit the form again.']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email:rfc|max:190',
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+\-\s().]{6,40}$/'],
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|min:10|max:3000',
            'consent' => 'accepted',
        ], ['consent.accepted' => 'Please confirm you agree to be contacted.']);

        if (preg_match('~https?://|www\.~i', $data['message']) && substr_count(strtolower($data['message']), 'http') > 2) {
            return back()->withInput()->withErrors(['message' => 'Please remove extra links from your message.']);
        }

        unset($data['consent']);
        $enquiry = Enquiry::create($data + ['ip_address' => $request->ip()]);

        if ($to = setting('notify_email')) {
            try {
                Mail::raw(
                    "New website enquiry\n\nName: {$enquiry->name}\nEmail: {$enquiry->email}\nPhone: {$enquiry->phone}\n\n{$enquiry->message}",
                    fn ($m) => $m->to($to)->replyTo($enquiry->email, $enquiry->name)->subject('New website enquiry from '.$enquiry->name)
                );
            } catch (\Throwable $e) {
                Log::warning('Enquiry mail failed: '.$e->getMessage());
            }
        }

        return redirect()->route('contact')->with('sent', true);
    }
}
