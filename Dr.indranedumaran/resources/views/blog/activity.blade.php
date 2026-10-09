@extends('layouts.app')

@section('content')
<x-page-header :title="$event->title" :eyebrow="$event->type_label" :crumbs="['Blogs' => route('blogs.index'), 'Activities' => route('activities.index'), \Illuminate\Support\Str::limit($event->title, 36) => null]">
    <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-mute">
        <span class="flex items-center gap-2"><x-icon name="calendar" class="h-4 w-4" />{{ $event->date_range }}</span>
        @if($event->venue || $event->city)<span class="flex items-center gap-2"><x-icon name="pin" class="h-4 w-4" />{{ trim($event->venue.', '.$event->city, ', ') }}</span>@endif
        @if($event->role)<span class="chip">{{ $event->role }}</span>@endif
        @if($event->is_placeholder)<span class="placeholder-badge">Sample entry – details to be confirmed</span>@endif
    </div>
</x-page-header>

<section class="section !pt-10" x-data="{ lightbox: null }">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <article class="lg:col-span-8">
            @if($event->cover_image)<img src="{{ media_url($event->cover_image) }}" alt="{{ $event->title }}" class="mb-8 aspect-[16/9] w-full rounded-3xl object-cover">@endif
            @if($event->summary)<p class="mb-8 font-display text-2xl leading-snug text-lilac">{{ $event->summary }}</p>@endif
            @if($event->body)<div class="rich">{!! $event->body !!}</div>@endif

            @if($embed)<div class="mt-10 aspect-video overflow-hidden rounded-3xl ring-1 ring-ice-200"><iframe src="{{ $embed }}" title="Video: {{ $event->title }}" class="h-full w-full" loading="lazy" allowfullscreen></iframe></div>@endif

            @if(!empty($event->photos))
                <h2 class="mt-12 text-3xl">Photo gallery</h2>
                <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach($event->photos as $p)
                        <button type="button" @click="lightbox = '{{ media_url($p) }}'" class="overflow-hidden rounded-2xl ring-1 ring-ice-200"><img src="{{ media_url($p) }}" alt="Photo from {{ $event->title }}" loading="lazy" class="aspect-square w-full object-cover transition hover:scale-105"></button>
                    @endforeach
                </div>
            @endif
        </article>

        <aside class="space-y-6 lg:col-span-4">
            @if($event->highlight_list)
                <div class="card bg-gradient-to-br from-ice-100 to-lavender-50">
                    <h2 class="text-2xl">Highlights</h2>
                    <ul class="mt-4 space-y-3 text-sm">@foreach($event->highlight_list as $h)<li class="flex gap-2"><x-icon name="sparkle" class="mt-0.5 h-4 w-4 shrink-0 text-copper" />{{ $h }}</li>@endforeach</ul>
                </div>
            @endif
            @if($others->isNotEmpty())
                <div class="card">
                    <h2 class="text-2xl">Other activities</h2>
                    <ul class="mt-4 space-y-3 text-sm">@foreach($others as $o)<li><a href="{{ route('activities.show', $o->slug) }}" class="block hover:text-lilac"><span class="text-xs text-mute">{{ $o->date_range }}</span><br>{{ $o->title }}</a></li>@endforeach</ul>
                </div>
            @endif
        </aside>
    </div>
    <div x-show="lightbox" x-cloak @click="lightbox = null" @keydown.escape.window="lightbox = null" class="fixed inset-0 z-[90] flex items-center justify-center bg-brand-900/80 p-4" role="dialog" aria-modal="true"><img :src="lightbox" alt="" class="max-h-[90vh] max-w-full rounded-2xl"></div>
</section>
@endsection
