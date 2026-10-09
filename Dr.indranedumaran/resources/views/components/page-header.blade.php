@props(['title', 'eyebrow' => null, 'intro' => null, 'crumbs' => []])
<section class="relative overflow-hidden bg-gradient-to-br from-ice-100 via-card to-lavender-50">
    <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-peach-100/60 blur-3xl"></div>
    <div class="container-x relative py-14 sm:py-20">
        @if($crumbs)
            <nav aria-label="Breadcrumb" class="mb-5 text-xs text-mute">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-lilac">Home</a></li>
                    @foreach($crumbs as $label => $url)
                        <li aria-hidden="true">/</li>
                        <li>@if($url)<a href="{{ $url }}" class="hover:text-lilac">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif</li>
                    @endforeach
                </ol>
            </nav>
        @endif
        @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
        <h1 class="max-w-3xl text-5xl leading-[1.08] sm:text-6xl">{{ $title }}</h1>
        @if($intro)<p class="lead mt-5 max-w-2xl">{{ $intro }}</p>@endif
        {{ $slot }}
    </div>
</section>
