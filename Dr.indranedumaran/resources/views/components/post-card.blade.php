@props(['post', 'tone' => 0])
<article class="group">
    <a href="{{ route('blogs.show', $post->slug) }}" class="card block h-full overflow-hidden !p-0">
        <x-cover :src="$post->image_url" :alt="$post->image_alt ?: $post->title" :label="$post->category?->name" :tone="$tone" class="rounded-t-3xl" />
        <div class="p-6">
            <div class="flex items-center gap-3 text-xs text-mute">
                @if($post->category)<span class="chip">{{ $post->category->name }}</span>@endif
                <span>{{ $post->reading_time }} min read</span>
            </div>
            <h3 class="mt-3 text-2xl leading-snug transition group-hover:text-lilac">{{ $post->title }}</h3>
            @if($post->excerpt)<p class="mt-2 line-clamp-3 text-sm leading-relaxed text-mute">{{ $post->excerpt }}</p>@endif
            <p class="mt-4 text-xs text-mute"><time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j M Y') }}</time> · {{ $post->author_name }}</p>
        </div>
    </a>
</article>
