@extends('admin.layout')
@section('title', 'Enquiries')

@section('content')
<form method="GET" class="mb-6 flex flex-wrap gap-2">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search…" class="a-input !w-56">
    <select name="status" class="a-input !w-40" onchange="this.form.submit()"><option value="">All statuses</option>@foreach(\App\Models\Enquiry::STATUSES as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach</select>
    <button class="a-btn a-btn-ghost">Filter</button>
</form>
<div class="overflow-x-auto rounded-2xl bg-card ring-1 ring-slate-200">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">From</th><th class="px-4 py-3">Message</th><th class="px-4 py-3">Received</th><th class="px-4 py-3">Status</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($enquiries as $e)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3"><a href="{{ route('admin.enquiries.show', $e) }}" class="font-medium text-slate-800 hover:text-lilac">{{ $e->name }}</a><br><span class="text-xs text-slate-500">{{ $e->email }}</span></td>
                    <td class="max-w-xs truncate px-4 py-3 text-slate-600">{{ $e->message }}</td>
                    <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $e->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3"><span class="a-badge {{ $e->status === 'new' ? 'bg-peach-100 text-copper' : 'bg-slate-100 text-slate-600' }}">{{ $e->status }}</span></td>
                </tr>
            @empty<tr><td colspan="4" class="px-4 py-12 text-center text-slate-500">No enquiries.</td></tr>@endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $enquiries->links() }}</div>
@endsection
