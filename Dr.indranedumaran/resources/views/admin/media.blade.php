@extends('admin.layout')
@section('title', 'Media library')

@section('content')
<form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="mb-8 flex flex-wrap items-end gap-4 rounded-2xl bg-card p-5 ring-1 ring-slate-200">
    @csrf
    <div class="min-w-64 flex-1">
        <label for="files" class="mb-1.5 block text-sm font-medium text-slate-700">Upload images</label>
        <input id="files" type="file" name="files[]" multiple required accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-ice-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-lilac">
        <p class="mt-1 text-xs text-slate-500">JPG, PNG, WebP · max 5 MB each · converted to optimised WebP.</p>
    </div>
    <button class="a-btn a-btn-primary">Upload</button>
</form>

<form method="GET" class="mb-5"><input type="search" name="q" value="{{ request('q') }}" placeholder="Search file name…" class="a-input !w-64"></form>

<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6" x-data="{ copied: null }">
    @forelse($items as $m)
        <div class="overflow-hidden rounded-xl bg-card ring-1 ring-slate-200">
            <img src="{{ $m->thumb_url }}" alt="{{ $m->alt }}" loading="lazy" class="aspect-square w-full object-cover">
            <div class="space-y-2 p-3 text-xs">
                <p class="truncate font-medium text-slate-700" title="{{ $m->original_name }}">{{ $m->original_name }}</p>
                <p class="text-slate-500">{{ $m->width }}×{{ $m->height }} · {{ number_format($m->size / 1024) }} KB</p>
                <form method="POST" action="{{ route('admin.media.update', $m) }}" class="flex gap-1">@csrf @method('PUT')<input name="alt" value="{{ $m->alt }}" placeholder="Alt text" class="a-input !px-2 !py-1 text-xs"><button class="a-btn a-btn-ghost !px-2 !py-1 text-xs">Save</button></form>
                <div class="flex gap-1">
                    <button type="button" class="a-btn a-btn-ghost flex-1 !px-2 !py-1 text-xs" @click="navigator.clipboard.writeText('{{ $m->url }}'); copied = {{ $m->id }}; setTimeout(() => copied = null, 1500)"><span x-text="copied === {{ $m->id }} ? 'Copied' : 'Copy URL'">Copy URL</span></button>
                    <form method="POST" action="{{ route('admin.media.destroy', $m) }}" onsubmit="return confirm('Delete this file? Content using it will show a broken image.')">@csrf @method('DELETE')<button class="a-btn a-btn-danger !px-2 !py-1 text-xs">Delete</button></form>
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-full py-12 text-center text-slate-500">No media yet.</p>
    @endforelse
</div>
<div class="mt-6">{{ $items->links() }}</div>
@endsection
