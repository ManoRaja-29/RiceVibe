<figure class="card flex h-full flex-col">
    <x-icon name="sparkle" class="h-6 w-6 text-brand-400" />
    <blockquote class="mt-4 flex-1 whitespace-pre-line text-sm leading-relaxed text-ink">{{ $s->body }}</blockquote>
    <figcaption class="mt-5 border-t border-ice-200 pt-4 text-sm">
        <span class="font-semibold text-heading">{{ $s->author_name }}</span>
        @if($s->visit_for)<span class="block text-xs text-mute">Visited for {{ $s->visit_for }}</span>@endif
        @if($s->source)<span class="block text-xs text-mute">{{ $s->source }}</span>@endif
    </figcaption>
</figure>
