@extends('layouts.app')

@section('content')
@php $weekly = request('view') === 'weekly'; @endphp
<x-page-header title="Blogs & health education" eyebrow="The journal" intro="Clear, medically responsible articles on women’s health, fertility, pregnancy and wellbeing." :crumbs="['Blogs' => null]">
    <form action="{{ route('blogs.index') }}" method="GET" role="search" class="mt-8 flex max-w-xl items-center gap-2 rounded-full bg-card p-1.5 ring-1 ring-sky-pastel focus-within:ring-2 focus-within:ring-brand-500">
        @if($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory->slug }}">@endif
        <label for="q" class="sr-only">Search articles</label>
        <x-icon name="search" class="ml-3 h-5 w-5 shrink-0 text-mute" />
        <input id="q" name="q" type="search" value="{{ $term }}" placeholder="Search articles…" class="w-full border-0 bg-transparent px-2 py-2 text-sm focus:ring-0">
        <button class="btn btn-primary !py-2">Search</button>
    </form>
</x-page-header>

<section class="section !pt-10">
    <div class="container-x">
        <div class="mb-8 inline-flex flex-wrap gap-1 rounded-full bg-ice-100 p-1" role="tablist" aria-label="Blog sections">
            <a href="{{ route('blogs.index') }}" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ !$weekly ? 'bg-brand-600 text-white' : 'text-mute hover:text-heading' }}">Articles</a>
            <a href="{{ route('blogs.index', ['view' => 'weekly']) }}" class="rounded-full px-5 py-2 text-sm font-semibold transition {{ $weekly ? 'bg-brand-600 text-white' : 'text-mute hover:text-heading' }}">Pregnancy week by week</a>
            <a href="{{ route('activities.index') }}" class="rounded-full px-5 py-2 text-sm font-semibold text-mute transition hover:text-heading">Conferences</a>
        </div>
@if($weekly)
        <p class="mb-6 max-w-2xl text-mute">Hover over (or tap) a week to see how your baby is growing and what you may notice.</p>
        <x-week-grid />
@else
        <div class="flex flex-wrap items-center gap-2" aria-label="Filter by category">
            <a href="{{ route('blogs.index') }}" class="chip {{ !$activeCategory ? 'chip-active' : '' }}">All</a>
            @foreach($categories as $cat)
                <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}" class="chip {{ $activeCategory?->id === $cat->id ? 'chip-active' : '' }}">{{ $cat->name }} <span class="ml-1 opacity-60">{{ $cat->posts_count }}</span></a>
            @endforeach
            <a href="{{ route('activities.index') }}" class="chip ml-auto !bg-peach-100 !text-heading">Conferences &amp; activities →</a>
        </div>

        @if($term)<p class="mt-6 text-sm text-mute">{{ $posts->total() }} result(s) for “{{ $term }}” <a href="{{ route('blogs.index') }}" class="ml-2 text-lilac underline">Clear</a></p>@endif

        @if($hero)
            <a href="{{ route('blogs.show', $hero->slug) }}" class="card group mt-8 grid overflow-hidden !p-0 md:grid-cols-2">
                <x-cover :src="$hero->image_url" :alt="$hero->image_alt ?: $hero->title" :label="$hero->category?->name" ratio="aspect-[16/11] md:aspect-auto md:min-h-80" />
                <div class="flex flex-col justify-center p-8 sm:p-12">
                    <span class="chip self-start">Latest</span>
                    <h2 class="mt-4 text-4xl leading-tight transition group-hover:text-lilac">{{ $hero->title }}</h2>
                    <p class="mt-3 leading-relaxed text-mute">{{ $hero->excerpt }}</p>
                    <p class="mt-5 text-xs text-mute">{{ $hero->published_at?->format('j M Y') }} · {{ $hero->reading_time }} min read</p>
                </div>
            </a>
        @endif

        @php $rest = $hero ? $posts->getCollection()->slice(1) : $posts->getCollection(); @endphp
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($rest as $i => $post)
                <x-post-card :post="$post" :tone="$i" />
            @empty
                @unless($hero)<p class="col-span-full py-16 text-center text-mute">No articles found. Try another search or category.</p>@endunless
            @endforelse
        </div>

        <div class="mt-12">{{ $posts->links() }}</div>
@endif
    </div>
</section>
@endsection
