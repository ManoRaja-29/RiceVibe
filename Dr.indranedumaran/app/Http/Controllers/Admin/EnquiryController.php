<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $q = Enquiry::query()->latest();
        if ($s = $request->query('status')) {
            $q->where('status', $s);
        }
        if ($t = trim((string) $request->query('q'))) {
            $q->where(fn ($w) => $w->where('name', 'like', "%$t%")->orWhere('email', 'like', "%$t%")->orWhere('message', 'like', "%$t%"));
        }

        return view('admin.enquiries.index', ['enquiries' => $q->paginate(20)->withQueryString()]);
    }

    public function show(Enquiry $enquiry)
    {
        if ($enquiry->status === 'new') {
            $enquiry->update(['status' => 'read']);
        }

        return view('admin.enquiries.show', ['enquiry' => $enquiry]);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $data = $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(Enquiry::STATUSES)),
            'admin_notes' => 'nullable|string|max:3000',
        ]);
        $enquiry->update($data);

        return back()->with('status', 'Enquiry updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('status', 'Enquiry deleted.');
    }
}
