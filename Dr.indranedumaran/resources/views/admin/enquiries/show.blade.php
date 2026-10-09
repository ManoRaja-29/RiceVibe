@extends('admin.layout')
@section('title', 'Enquiry from '.$enquiry->name)

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <section class="rounded-2xl bg-card p-6 ring-1 ring-slate-200">
        <dl class="grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="text-xs uppercase text-slate-500">Name</dt><dd class="font-medium">{{ $enquiry->name }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Phone</dt><dd>{{ $enquiry->phone ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Email</dt><dd><a class="text-lilac" href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Topic</dt><dd>{{ $enquiry->subject ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">Received</dt><dd>{{ $enquiry->created_at->format('d M Y H:i') }}</dd></div>
            <div><dt class="text-xs uppercase text-slate-500">IP</dt><dd>{{ $enquiry->ip_address }}</dd></div>
        </dl>
        <h2 class="mt-6 font-sans text-sm font-semibold">Message</h2>
        <p class="mt-2 whitespace-pre-line rounded-xl bg-slate-50 p-4 text-sm leading-relaxed">{{ $enquiry->message }}</p>
    </section>

    <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="space-y-4 rounded-2xl bg-card p-6 ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div><label class="mb-1.5 block text-sm font-medium" for="status">Status</label><select id="status" name="status" class="a-input">@foreach(\App\Models\Enquiry::STATUSES as $k => $l)<option value="{{ $k }}" @selected($enquiry->status === $k)>{{ $l }}</option>@endforeach</select></div>
        <div><label class="mb-1.5 block text-sm font-medium" for="notes">Internal notes</label><textarea id="notes" name="admin_notes" rows="4" class="a-input">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea></div>
        <div class="flex justify-between"><a href="{{ route('admin.enquiries.index') }}" class="a-btn a-btn-ghost">← Back</a><button class="a-btn a-btn-primary">Save</button></div>
    </form>
    <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry permanently?')">@csrf @method('DELETE')<button class="a-btn a-btn-danger">Delete enquiry</button></form>
</div>
@endsection
