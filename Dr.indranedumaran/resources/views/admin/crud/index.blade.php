@extends('admin.layout')
@section('title', $title)

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <form method="GET" class="flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search…" class="a-input !w-56" aria-label="Search">
        @foreach($filters as $field => $f)
            <select name="{{ $field }}" class="a-input !w-44" aria-label="{{ $f['label'] }}" onchange="this.form.submit()">
                <option value="">{{ $f['label'] }}</option>
                @foreach($f['options'] as $val => $lab)<option value="{{ $val }}" @selected((string) request($field) === (string) $val)>{{ $lab }}</option>@endforeach
            </select>
        @endforeach
        <button class="a-btn a-btn-ghost">Filter</button>
    </form>
    <a href="{{ route($route.'.create') }}" class="a-btn a-btn-primary">+ New {{ $singular }}</a>
</div>

<div class="overflow-x-auto rounded-2xl bg-card ring-1 ring-slate-200">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>@foreach($columns as $c)<th class="px-4 py-3 font-medium">{{ $c[0] }}</th>@endforeach<th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($records as $r)
                <tr class="hover:bg-slate-50">
                    @foreach($columns as $c)
                        @php $v = $c[1]($r); @endphp
                        <td class="px-4 py-3 {{ $loop->first ? 'font-medium text-slate-800' : 'text-slate-600' }}">
                            @if(($c[2] ?? null) === 'badge')
                                <span class="a-badge {{ ['published' => 'bg-emerald-50 text-emerald-700', 'draft' => 'bg-slate-100 text-slate-600', 'scheduled' => 'bg-amber-50 text-amber-700', 'placeholder' => 'bg-peach-100 text-copper', 'verified' => 'bg-emerald-50 text-emerald-700'][$v] ?? 'bg-slate-100 text-slate-600' }}">{{ $v }}</span>
                            @elseif($loop->first)
                                <a href="{{ route($route.'.edit', $r->getKey()) }}" class="hover:text-lilac">{{ $v }}</a>
                            @else{{ $v }}@endif
                        </td>
                    @endforeach
                    <td class="whitespace-nowrap px-4 py-3 text-right">
                        <a href="{{ route($route.'.edit', $r->getKey()) }}" class="a-btn a-btn-ghost !px-3 !py-1">Edit</a>
                        <form method="POST" action="{{ route($route.'.destroy', $r->getKey()) }}" class="inline" onsubmit="return confirm('Delete this {{ $singular }}? This cannot be undone.')">@csrf @method('DELETE')<button class="a-btn a-btn-danger !px-3 !py-1">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($columns) + 1 }}" class="px-4 py-12 text-center text-slate-500">Nothing here yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $records->links() }}</div>
@endsection
