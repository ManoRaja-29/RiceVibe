@props(['src' => null, 'alt' => '', 'label' => null, 'ratio' => 'aspect-[16/10]', 'tone' => 0])
@php
$tones = ['from-ice-200 via-sky-pastel/60 to-lavender-100', 'from-lavender-100 via-ice-100 to-peach-100', 'from-peach-100 via-lavender-50 to-ice-200'];
@endphp
<div {{ $attributes->merge(['class' => "relative w-full overflow-hidden $ratio"]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
    @else
        <div class="absolute inset-0 bg-gradient-to-br {{ $tones[$tone % 3] }}"></div>
        <svg class="absolute -right-6 -top-6 h-40 w-40 text-white/50" viewBox="0 0 100 100" fill="currentColor" aria-hidden="true"><circle cx="50" cy="50" r="50"/></svg>
        @if($label)<span class="absolute bottom-4 left-5 font-display text-2xl text-lilac/70">{{ $label }}</span>@endif
    @endif
</div>
