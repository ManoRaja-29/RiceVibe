@extends('layouts.app')

@section('content')
@php
$photo = media_url(setting('doctor_photo')) ?: asset('images/doctor.webp');
$sections = ['fertility' => ['Fertility', 'flower', 'fertility'], 'gynaecology' => ['Gynaecology', 'shield', 'gynaecology'], 'maternity' => ['Pregnancy & maternity', 'baby', 'maternity']];
$sectionBlurb = [
    'fertility' => 'Assessment, PCOS care and treatment options explained clearly.',
    'gynaecology' => 'Periods, fibroids, endometriosis, menopause and surgical care.',
    'maternity' => 'Antenatal check-ups, birth planning and postpartum support.',
];
$heroLinks = fn ($u) => \Illuminate\Support\Str::startsWith($u, ['http', '/']) ? $u : '/'.$u;
@endphp

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-ice-100 via-card to-lavender-50">
    <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-sky-pastel/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-16 bottom-0 h-72 w-72 rounded-full bg-peach-100/70 blur-3xl"></div>
    <div class="container-x relative grid items-center gap-10 py-14 sm:py-20 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-7">
            <span class="eyebrow">{{ setting('hero_eyebrow') }}</span>
            <h1 class="text-5xl leading-[1.05] sm:text-6xl lg:text-7xl">{{ setting('hero_title') }}</h1>
            <p class="lead mt-6 max-w-xl">{{ setting('hero_text') }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('blogs.index') }}" class="btn btn-primary">Read our blogs <x-icon name="arrow" class="h-4 w-4" /></a>
            </div>
            <dl class="mt-10 grid max-w-lg grid-cols-3 gap-4 text-sm">
                @foreach([['Women', 'heart'], ['Maternity', 'baby'], ['Wellness', 'leaf']] as [$t, $i])
                    <div class="flex items-center gap-2 text-mute"><x-icon :name="$i" class="h-5 w-5 text-lilac" /><dt>{{ $t }}</dt></div>
                @endforeach
            </dl>
        </div>

        <div class="relative mx-auto w-full max-w-sm lg:col-span-5 lg:max-w-none">
            <div class="relative mx-auto aspect-[4/5] w-full max-w-md overflow-hidden rounded-t-[999px] rounded-b-[2.5rem] bg-gradient-to-b from-sky-pastel/70 via-ice-100 to-lavender-100 ring-1 ring-white shadow-[0_30px_70px_-30px_rgba(122,63,194,0.45)]">
                <img src="{{ $photo }}" alt="{{ setting('doctor_photo_alt') }}" width="524" height="634" fetchpriority="high"
                     class="absolute inset-x-0 bottom-0 h-full w-full object-cover object-top">
            </div>
            <div class="absolute -bottom-4 left-0 rounded-2xl bg-card px-5 py-3 shadow-lg ring-1 ring-ice-200 sm:-left-4">
                <p class="font-display text-lg font-semibold text-lilac">{{ setting('doctor_name') }}</p>
                <p class="text-xs text-mute">{{ setting('doctor_title') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT PREVIEW --}}
<section class="section">
    <div class="container-x grid items-center gap-12 lg:grid-cols-2">
        <div class="relative order-2 lg:order-1">
            <div class="rounded-[2.5rem] bg-gradient-to-br from-brand-600 via-lavender-100 to-peach-100 p-6 shadow-2xl ring-1 ring-white/30 sm:p-10"><img src="{{ asset('images/logo-full.webp') }}" alt="{{ setting('site_name') }} logo – Women, Maternity, Fertility, Wellness, Beyond" width="1400" height="389" loading="lazy" class="mx-auto h-auto w-full rounded-3xl bg-[#fff] p-4 shadow-xl"><div class="mt-6 flex flex-wrap justify-center gap-2 text-xs font-semibold"><span class="chip">28+ years</span><span class="chip">TNMC 60859</span><span class="chip">FOGSI · IMA · OGSSI</span></div></div>
        </div>
        <div class="order-1 lg:order-2">
            <span class="eyebrow">About the doctor</span>
            <h2 class="h-section">{{ setting('about_preview_title') }}</h2>
            <p class="lead mt-5">{{ setting('about_preview_text') }}</p>
            <ul class="mt-6 space-y-3 text-sm text-ink">
                @foreach(['Clear, unhurried explanations', 'Evidence-based recommendations', 'Respectful, shared decision-making'] as $point)
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-ice-100 text-lilac"><x-icon name="check" class="h-4 w-4" /></span>{{ $point }}</li>
                @endforeach
            </ul>
            <a href="{{ route('about') }}" class="btn btn-soft mt-8">Read full profile <x-icon name="arrow" class="h-4 w-4" /></a>
        </div>
    </div>
</section>

{{-- EXPERTISE --}}
<section class="section bg-ice-50">
    <div class="container-x">
        <div class="mx-auto max-w-2xl text-center">
            <span class="eyebrow">Expertise</span>
            <h2 class="h-section">{{ setting('expertise_title') }}</h2>
            <p class="lead mt-4">{{ setting('expertise_text') }}</p>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach($sections as $key => [$label, $icon, $route])
                <div class="card flex flex-col">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ ['fertility' => 'bg-peach-100', 'gynaecology' => 'bg-ice-100', 'maternity' => 'bg-lavender-100'][$key] }} text-lilac"><x-icon :name="$icon" class="h-6 w-6" /></span>
                    <h3 class="mt-5 text-3xl">{{ $label }}</h3>
                    <p class="mt-2 text-sm text-mute">{{ $sectionBlurb[$key] }}</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach(($services[$key] ?? collect())->take(4) as $s)
                            <li><a href="{{ route($route.'.show', $s->slug) }}" class="flex items-center gap-2 text-ink hover:text-lilac"><x-icon name="check" class="h-4 w-4 text-brand-400" />{{ $s->title }}</a></li>
                        @endforeach
                    </ul>
                    <a href="{{ route($route) }}" class="mt-auto inline-flex items-center gap-2 pt-6 text-sm font-semibold text-lilac">Explore {{ strtolower($label) }} <x-icon name="arrow" class="h-4 w-4" /></a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PREGNANCY JOURNEY --}}
@if($stages->isNotEmpty())
@php
$stageIcons = ['calendar', 'flower', 'heart', 'sun', 'baby', 'leaf'];
$stageTints = ['from-brand-500 to-sky-pastel', 'from-brand-500 to-copper', 'from-copper to-peach-300', 'from-sky-pastel to-brand-500', 'from-copper to-brand-500', 'from-brand-400 to-peach-300'];
@endphp
<section class="section relative overflow-hidden">
    <div class="pointer-events-none absolute left-1/2 top-0 h-80 w-[40rem] -translate-x-1/2 rounded-full bg-copper/20 blur-3xl"></div>
    <div class="container-x relative">
        <div class="mx-auto max-w-2xl text-center">
            <span class="eyebrow">Pregnancy journey</span>
            <h2 class="h-section">{{ setting('pregnancy_title') }}</h2>
            <p class="lead mt-4">{{ setting('pregnancy_text') }}</p>
        </div>
        <ol class="mt-14 grid gap-x-6 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($stages as $i => $stage)
                <li class="group relative rounded-[2rem] bg-card/80 p-7 pt-12 text-center ring-1 ring-ice-200 backdrop-blur transition duration-300 hover:-translate-y-1 hover:ring-brand-400">
                    <span class="absolute -top-8 left-1/2 flex h-16 w-16 -translate-x-1/2 items-center justify-center rounded-2xl bg-gradient-to-br {{ $stageTints[$i % 6] }} text-white shadow-xl shadow-brand-700/40 ring-4 ring-lavender-50 transition duration-300 group-hover:rotate-6">
                        <x-icon :name="$stageIcons[$i % 6]" class="h-7 w-7" />
                    </span>
                    <span class="font-display text-sm font-semibold tracking-[0.3em] text-peach-300">STEP {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    @if($stage->label)<p class="mt-1 text-xs font-semibold uppercase tracking-widest text-lilac/80">{{ $stage->label }}</p>@endif
                    <h3 class="mt-3 text-3xl">{{ $stage->title }}</h3>
                    <p class="mx-auto mt-3 max-w-xs text-sm leading-relaxed text-mute">{{ $stage->summary }}</p>
                </li>
            @endforeach
        </ol>
        <div class="mt-12 text-center"><a href="{{ route('maternity') }}" class="btn btn-primary">Pregnancy &amp; maternity care <x-icon name="arrow" class="h-4 w-4" /></a></div>
    </div>
</section>
@endif
{{-- TRIMESTER CHART --}}
<section class="section !pb-0"><div class="container-x"><x-trimester-chart /></div></section>

{{-- FEATURED BLOGS --}}
@if($featured->isNotEmpty())
<section class="section bg-gradient-to-b from-card to-ice-50">
    <div class="container-x">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="max-w-xl">
                <span class="eyebrow">Learn</span>
                <h2 class="h-section">Featured articles</h2>
            </div>
            <a href="{{ route('blogs.index') }}" class="text-sm font-semibold text-lilac">All articles →</a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($featured as $i => $post)<x-post-card :post="$post" :tone="$i" />@endforeach
        </div>
    </div>
</section>
@endif

{{-- CONFERENCES --}}
@if($conferences->isNotEmpty())
<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <span class="eyebrow">Conferences &amp; activities</span>
            <h2 class="h-section">{{ setting('conferences_title') }}</h2>
            <p class="lead mt-4">{{ setting('conferences_text') }}</p>
            <a href="{{ route('activities.index') }}" class="btn btn-soft mt-8">View the full timeline</a>
        </div>
        <ol class="relative space-y-6 border-l border-sky-pastel pl-8 lg:col-span-8">
            @foreach($conferences as $c)
                <li class="relative">
                    <span class="absolute -left-[2.55rem] top-6 h-4 w-4 rounded-full border-4 border-white bg-brand-500 ring-1 ring-sky-pastel"></span>
                    <a href="{{ route('activities.show', $c->slug) }}" class="card block">
                        <div class="flex flex-wrap items-center gap-2 text-xs text-mute">
                            <span class="chip">{{ $c->type_label }}</span><span>{{ $c->date_range }}</span>
                            @if($c->is_placeholder)<span class="placeholder-badge">Sample entry</span>@endif
                        </div>
                        <h3 class="mt-3 text-2xl">{{ $c->title }}</h3>
                        <p class="mt-1 text-sm text-mute">{{ trim($c->venue.', '.$c->city, ', ') }}</p>
                        @if($c->summary)<p class="mt-3 text-sm leading-relaxed text-mute">{{ \Illuminate\Support\Str::limit($c->summary, 160) }}</p>@endif
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- ROBOTIC SURGERY SPOTLIGHT --}}
<section class="section">
    <div class="container-x">
        <div class="overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-brand-900 via-brand-700 to-brand-600 text-white">
            <div class="grid items-center gap-10 p-8 sm:p-14 lg:grid-cols-2">
                <div>
                    <span class="mb-3 inline-block text-xs font-semibold uppercase tracking-[0.22em] text-peach-300">Educational spotlight</span>
                    <h2 class="font-display text-4xl leading-tight !text-white sm:text-5xl">{{ setting('robotic_title') }}</h2>
                    <p class="mt-5 leading-relaxed text-white/80">{{ setting('robotic_text') }}</p>
                    <ul class="mt-6 grid gap-3 text-sm sm:grid-cols-2">
                        @foreach(array_filter(array_map('trim', preg_split('/\R/', (string) setting('robotic_points')))) as $pt)
                            <li class="flex gap-2 text-white/90"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-peach-300" />{{ $pt }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-6 text-xs text-white/60">General information only. Suitability for any procedure is a personal medical decision.</p>
                </div>
                <div>
                    <figure class="relative"><div class="absolute -inset-3 rounded-[2rem] bg-gradient-to-br from-copper/60 to-sky-pastel/60 blur-xl" aria-hidden="true"></div><img src="{{ setting('robotic_image') ? media_url(setting('robotic_image')) : asset('images/robotic-spotlight.webp') }}" alt="Surgeons operating a robotic-assisted surgical console in an operating theatre" width="1400" height="934" loading="lazy" class="relative aspect-[3/2] w-full rounded-3xl object-cover object-center shadow-2xl ring-1 ring-white/30"><figcaption class="relative mt-2 text-right text-[11px] text-white/60">Illustrative image of a robotic surgical console</figcaption></figure>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- GALLERY --}}
@if($gallery->isNotEmpty())
<section class="section bg-ice-50">
    <div class="container-x" x-data="{ lightbox: null }">
        <div class="mx-auto max-w-2xl text-center">
            <span class="eyebrow">Gallery</span>
            <h2 class="h-section">{{ setting('gallery_title') }}</h2>
        </div>
        <div class="mt-10 grid grid-cols-2 gap-4 md:grid-cols-4">
            @foreach($gallery as $i => $g)
                @php $big = $i === 0 || $i === 5; @endphp
                <figure class="group relative overflow-hidden rounded-3xl ring-1 ring-ice-200 {{ $big ? 'col-span-2 row-span-2' : '' }}">
                    @if($g->image)
                        <button type="button" class="block h-full w-full" @click="lightbox = '{{ media_url($g->image) }}'" aria-label="Enlarge: {{ $g->caption }}">
                            <x-cover :src="media_url($g->image)" :alt="$g->caption" :ratio="$big ? 'aspect-square' : 'aspect-square'" />
                        </button>
                    @else
                        <x-cover :label="$g->caption" :tone="$i" ratio="aspect-square" />
                    @endif
                    <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-brand-900/70 to-transparent p-3 text-xs text-white">{{ $g->caption }}</figcaption>
                </figure>
            @endforeach
        </div>
        <div x-show="lightbox" x-cloak @click="lightbox = null" @keydown.escape.window="lightbox = null" class="fixed inset-0 z-[90] flex items-center justify-center bg-brand-900/80 p-4" role="dialog" aria-modal="true">
            <img :src="lightbox" alt="" class="max-h-[90vh] max-w-full rounded-2xl">
        </div>
    </div>
</section>
@endif

{{-- STORIES + CONTACT --}}
@php $stories = \App\Models\Story::published()->limit(3)->get(); @endphp
<section class="section bg-ice-50">
    <div class="container-x grid items-stretch gap-6 lg:grid-cols-2">
        @if($stories->isNotEmpty())
        <div class="flex flex-col" x-data="{ n: 0, c: {{ $stories->count() }}, t: null, start() { if (!matchMedia('(prefers-reduced-motion: reduce)').matches) this.t = setInterval(() => this.n = (this.n + 1) % this.c, 4000) }, stop() { clearInterval(this.t) } }" x-init="start()" @mouseenter="stop()" @mouseleave="start()" @focusin="stop()" @focusout="start()" aria-roledescription="carousel" aria-label="Patient stories">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div><span class="eyebrow">Stories</span><h2 class="text-4xl">Patient stories</h2></div>
                <a href="{{ route('stories') }}" class="btn btn-soft">Read more stories</a>
            </div>
            <div class="mt-6 grid flex-1 [&>*]:col-start-1 [&>*]:row-start-1">
                @foreach($stories as $k => $s)
                    <div class="h-full transition-opacity duration-500" :class="n === {{ $k }} ? 'opacity-100' : 'pointer-events-none opacity-0'" :aria-hidden="n !== {{ $k }}">@include('partials.story-card', ['s' => $s])</div>
                @endforeach
            </div>
            <div class="mt-5 flex justify-center gap-2">
                @foreach($stories as $k => $s)
                    <button type="button" @click="n = {{ $k }}" class="h-2.5 rounded-full transition-all" :class="n === {{ $k }} ? 'w-8 bg-brand-400' : 'w-2.5 bg-brand-400/40'" aria-label="Show story {{ $k + 1 }}"></button>
                @endforeach
            </div>
        </div>
        @endif

        <div class="rounded-[2rem] bg-gradient-to-br from-brand-700 via-brand-600 to-copper p-8 text-white ring-1 ring-white/10 sm:p-10 {{ $stories->isEmpty() ? 'lg:col-span-2' : '' }}">
            <span class="mb-3 inline-block text-xs font-semibold uppercase tracking-[0.22em] text-peach-300">Contact</span>
            <h2 class="text-3xl !text-white sm:text-4xl">{{ setting('contact_title') }}</h2>
            <p class="mt-3 text-white/85">{{ setting('contact_text') }}</p>
            <ul class="mt-6 grid gap-3 text-sm sm:grid-cols-2">
                <li class="flex items-start gap-3 rounded-2xl bg-card/10 p-4 sm:col-span-2"><x-icon name="pin" class="mt-0.5 h-5 w-5 shrink-0 text-peach-300" /><span class="whitespace-pre-line">{{ setting('address') }}</span></li>
                <li class="flex items-center gap-3 rounded-2xl bg-card/10 p-4"><x-icon name="phone" class="h-5 w-5 text-peach-300" /><a href="tel:{{ preg_replace('/[^\d+]/', '', setting('phone')) }}">{{ setting('phone') }}</a></li>
                @if(setting('email'))<li class="flex items-center gap-3 rounded-2xl bg-card/10 p-4"><x-icon name="mail" class="h-5 w-5 text-peach-300" /><a href="mailto:{{ setting('email') }}" class="break-all">{{ setting('email') }}</a></li>@endif
                @if(setting('hours'))<li class="flex items-start gap-3 rounded-2xl bg-card/10 p-4"><x-icon name="clock" class="mt-0.5 h-5 w-5 text-peach-300" /><span class="whitespace-pre-line">{{ setting('hours') }}</span></li>@endif
            </ul>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="btn bg-[#fff] text-[#4e2190] hover:bg-[#fbe3f0]">Contact us</a>
                @if(setting('whatsapp'))<a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" class="btn bg-card/15 text-white ring-1 ring-white/40 hover:bg-card/25"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp</a>@endif
            </div>
        </div>
    </div>
</section>@endsection
