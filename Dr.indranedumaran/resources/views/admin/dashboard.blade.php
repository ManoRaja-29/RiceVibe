@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as [$label, $value, $url])
        <a href="{{ $url }}" class="rounded-2xl bg-card p-5 ring-1 ring-slate-200 transition hover:ring-brand-400">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="mt-2 font-display text-4xl font-semibold text-lilac">{{ $value }}</p>
        </a>
    @endforeach
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <section class="rounded-2xl bg-card p-6 ring-1 ring-slate-200 lg:col-span-2">
        <div class="flex items-center justify-between"><h2 class="font-sans text-base font-semibold">Recent enquiries</h2><a href="{{ route('admin.enquiries.index') }}" class="text-sm text-lilac">View all</a></div>
        <ul class="mt-4 divide-y divide-slate-100 text-sm">
            @forelse($enquiries as $e)
                <li class="flex items-center justify-between gap-3 py-3">
                    <a href="{{ route('admin.enquiries.show', $e) }}" class="min-w-0"><span class="font-medium">{{ $e->name }}</span> <span class="text-slate-500">· {{ ucfirst($e->type) }}</span><br><span class="block truncate text-slate-500">{{ \Illuminate\Support\Str::limit($e->message, 80) }}</span></a>
                    <span class="a-badge shrink-0 {{ $e->status === 'new' ? 'bg-peach-100 text-copper' : 'bg-slate-100 text-slate-600' }}">{{ $e->status }}</span>
                </li>
            @empty<li class="py-6 text-slate-500">No enquiries yet.</li>@endforelse
        </ul>
    </section>

    <div class="space-y-6">
        <section class="rounded-2xl bg-card p-6 ring-1 ring-slate-200">
            <h2 class="font-sans text-base font-semibold">Most viewed posts</h2>
            <ol class="mt-4 space-y-3 text-sm">@forelse($topPosts as $p)<li class="flex justify-between gap-3"><a href="{{ route('admin.posts.edit', $p) }}" class="truncate hover:text-lilac">{{ $p->title }}</a><span class="text-slate-500">{{ number_format($p->views) }}</span></li>@empty<li class="text-slate-500">No posts yet.</li>@endforelse</ol>
        </section>
        <section class="rounded-2xl bg-card p-6 ring-1 ring-slate-200">
            <h2 class="font-sans text-base font-semibold">Scheduled posts</h2>
            <ul class="mt-4 space-y-3 text-sm">@forelse($scheduled as $p)<li><a href="{{ route('admin.posts.edit', $p) }}" class="hover:text-lilac">{{ $p->title }}</a><br><span class="text-xs text-slate-500">{{ $p->published_at->format('d M Y H:i') }}</span></li>@empty<li class="text-slate-500">Nothing scheduled.</li>@endforelse</ul>
        </section>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <a href="{{ route('admin.posts.create') }}" class="a-btn a-btn-primary">+ New blog post</a>
    <a href="{{ route('admin.conferences.create') }}" class="a-btn a-btn-ghost">+ New conference</a>
    <a href="{{ route('admin.media.index') }}" class="a-btn a-btn-ghost">Upload media</a>
</div>
@endsection
