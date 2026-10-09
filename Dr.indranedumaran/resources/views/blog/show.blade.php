@extends('layouts.app')

@section('content')
@php
$url = route('blogs.show', $post->slug);
$shares = [
    ['Facebook', 'facebook', 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($url)],
    ['X', 'x', 'https://twitter.com/intent/tweet?url='.urlencode($url).'&text='.urlencode($post->title)],
    ['LinkedIn', 'linkedin', 'https://www.linkedin.com/sharing/share-offsite/?url='.urlencode($url)],
    ['WhatsApp', 'whatsapp', 'https://wa.me/?text='.urlencode($post->title.' '.$url)],
];
@endphp
<x-page-header :title="$post->title" :eyebrow="$post->category?->name" :crumbs="['Blogs' => route('blogs.index'), \Illuminate\Support\Str::limit($post->title, 40) => null]">
    <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-mute">
        <span class="flex items-center gap-2"><img src="{{ asset('images/logo-mark.webp') }}" alt="" class="h-8 w-8 rounded-full bg-card object-cover ring-1 ring-sky-pastel">{{ $post->author_name }}</span>
        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j F Y') }}</time>
        <span class="flex items-center gap-1"><x-icon name="clock" class="h-4 w-4" />{{ $post->reading_time }} min read</span>
    </div>
</x-page-header>

<section class="section !pt-10">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <article class="lg:col-span-8">
            @if($post->image_url)
                <img src="{{ $post->image_url }}" alt="{{ $post->image_alt ?: $post->title }}" class="mb-10 aspect-[16/9] w-full rounded-3xl object-cover" fetchpriority="high">
            @endif
            @if($embed)
                <div class="mb-10 aspect-video overflow-hidden rounded-3xl ring-1 ring-ice-200"><iframe src="{{ $embed }}" title="Video: {{ $post->title }}" class="h-full w-full" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe></div>
            @endif
            @if($post->excerpt)<p class="mb-8 font-display text-2xl leading-snug text-lilac">{{ $post->excerpt }}</p>@endif

            <div class="rich">{!! $post->body !!}</div>

            <div class="mt-12 flex flex-wrap items-center gap-3 border-t border-ice-200 pt-6" x-data="{ copied: false }">
                <span class="mr-2 flex items-center gap-2 text-sm font-medium text-heading"><x-icon name="share" class="h-4 w-4" /> Share</span>
                @foreach($shares as [$label, $icon, $href])
                    <a href="{{ $href }}" target="_blank" rel="noopener" aria-label="Share on {{ $label }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-ice-100 text-lilac transition hover:bg-brand-600 hover:text-white"><x-icon :name="$icon" /></a>
                @endforeach
                <button type="button" @click="navigator.clipboard.writeText('{{ $url }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="flex h-10 items-center gap-2 rounded-full bg-ice-100 px-4 text-sm text-lilac hover:bg-brand-600 hover:text-white"><x-icon name="link" class="h-4 w-4" /><span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span></button>
            </div>

            <p class="mt-8 rounded-2xl bg-ice-50 p-5 text-sm leading-relaxed text-mute ring-1 ring-ice-200">This article is for general information and education. It is not a diagnosis or a substitute for advice from a qualified doctor who knows your history.</p>
        </article>

        <aside class="space-y-6 lg:col-span-4">
            <div class="card bg-gradient-to-br from-ice-100 to-lavender-50 lg:sticky lg:top-28">
                <h2 class="text-2xl">Questions about your health?</h2>
                <p class="mt-2 text-sm text-mute">Send an enquiry to {{ setting('doctor_name') }}'s clinic.</p>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-5 w-full">Contact us</a>
            </div>
        </aside>
    </div>
</section>

@if($related->isNotEmpty())
<section class="section bg-ice-50 !py-16">
    <div class="container-x">
        <h2 class="text-4xl">Keep reading</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-3">@foreach($related as $i => $r)<x-post-card :post="$r" :tone="$i" />@endforeach</div>
    </div>
</section>
@endif
@endsection
