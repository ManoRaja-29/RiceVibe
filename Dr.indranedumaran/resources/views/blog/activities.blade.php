@extends('layouts.app')

@section('content')
<x-page-header title="Conferences & activities" eyebrow="Professional activities" intro="A timeline of conferences, workshops and events — with highlights and photographs." :crumbs="['Blogs' => route('blogs.index'), 'Conferences & activities' => null]" />

<section class="section">
    <div class="container-x max-w-4xl">
        @forelse($byYear as $year => $events)
            <div class="mb-14">
                <h2 class="font-display text-5xl text-lilac/30">{{ $year }}</h2>
                <ol class="relative mt-6 space-y-8 border-l border-sky-pastel pl-8">
                    @foreach($events as $e)
                        <li class="relative">
                            <span class="absolute -left-[2.55rem] top-8 h-4 w-4 rounded-full border-4 border-white bg-brand-500 ring-1 ring-sky-pastel"></span>
                            <a href="{{ route('activities.show', $e->slug) }}" class="card group block overflow-hidden !p-0 sm:flex">
                                @if($e->cover_image)<x-cover :src="media_url($e->cover_image)" :alt="$e->title" ratio="aspect-[16/9] sm:aspect-auto sm:w-64 sm:shrink-0" />@endif
                                <div class="p-6">
                                    <div class="flex flex-wrap items-center gap-2 text-xs text-mute">
                                        <span class="chip">{{ $e->type_label }}</span><span>{{ $e->date_range }}</span>
                                        @if($e->is_placeholder)<span class="placeholder-badge">Sample entry</span>@endif
                                    </div>
                                    <h3 class="mt-3 text-2xl transition group-hover:text-lilac">{{ $e->title }}</h3>
                                    <p class="mt-1 text-sm text-mute">{{ trim($e->venue.', '.$e->city, ', ') }}@if($e->role) · {{ $e->role }}@endif</p>
                                    @if($e->summary)<p class="mt-3 text-sm leading-relaxed text-mute">{{ $e->summary }}</p>@endif
                                    @if($e->highlight_list)
                                        <ul class="mt-3 space-y-1 text-sm text-ink">@foreach(array_slice($e->highlight_list, 0, 2) as $h)<li class="flex gap-2"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-400" />{{ $h }}</li>@endforeach</ul>
                                    @endif
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>
        @empty
            <p class="text-center text-mute">Events will be listed here soon.</p>
        @endforelse
    </div>
</section>
@endsection
