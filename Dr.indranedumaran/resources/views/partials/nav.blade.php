@php
$weekPost = route('blogs.index', ['view' => 'weekly']);
$links = [
    ['Home', route('home'), request()->routeIs('home'), 'heart'],
    ['About', route('about'), request()->routeIs('about'), 'sparkle'],
    ['Fertility', route('fertility'), request()->routeIs('fertility*'), 'flower'],
    ['Gynaecology', route('gynaecology'), request()->routeIs('gynaecology*'), 'shield'],
    ['Blogs', route('blogs.index'), request()->routeIs('blogs.*', 'activities.*'), 'leaf'],
    ['Stories', route('stories'), request()->routeIs('stories'), 'chat'],
    ['Contact', route('contact'), request()->routeIs('contact'), 'phone'],
    ['Consult Q&A', route('qa.index'), request()->routeIs('qa.*'), 'chat'],
];
$blogMenu = [
    ['All articles', route('blogs.index'), 'leaf', 'Educational reading on women’s health'],
    ['Pregnancy week by week', $weekPost, 'baby', 'Weeks 1 to 40 at a glance'],
    ['Pregnancy articles', route('blogs.index', ['q' => 'trimester']), 'heart', 'Trimester guides'],
    ['Conferences & events', route('activities.index'), 'calendar', 'Talks and academic activity'],
];
$tel = preg_replace('/[^\d+]/', '', setting('phone'));
@endphp
<div class="contents" x-data="{ y: 0, p: 0, open: false, blog: false, find: false, u() { this.y = window.scrollY; const h = document.documentElement.scrollHeight - innerHeight; this.p = h > 0 ? Math.min(100, this.y / h * 100) : 0 } }" @keydown.escape.window="open = false; blog = false; find = false" x-init="u()" @scroll.window.passive="u()">
    <div class="fixed inset-x-0 top-0 z-[60] h-1 print:hidden" aria-hidden="true"><div class="h-full bg-gradient-to-r from-brand-500 via-copper to-sky-pastel transition-[width] duration-150" :style="`width:${p}%`"></div></div>

    <div class="hidden bg-brand-900 text-xs text-white/90 transition-all duration-300 md:block print:hidden" :class="y > 40 ? 'h-0 overflow-hidden opacity-0' : 'py-2'">
        <div class="container-x flex items-center justify-between gap-4">
            <p class="flex items-center gap-2 text-white/80"><x-icon name="sparkle" class="h-3.5 w-3.5 text-peach-300" />Gynaecology · Fertility · Maternity care</p>
            <div class="flex shrink-0 items-center gap-5">
                <a href="tel:{{ $tel }}" class="flex items-center gap-1.5 font-medium hover:text-peach-300"><x-icon name="phone" class="h-3.5 w-3.5" /> {{ setting('phone') }}</a>
                @if(setting('whatsapp'))<a href="https://wa.me/{{ preg_replace('/\D/', '', setting('whatsapp')) }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:text-peach-300"><x-icon name="whatsapp" class="h-3.5 w-3.5" /> WhatsApp</a>@endif
            </div>
        </div>
    </div>

    <header class="sticky top-0 z-50 border-b border-white/20 bg-gradient-to-r from-brand-900/95 via-lavender-100/95 to-peach-100/90 backdrop-blur-xl transition-all duration-300 print:hidden" :class="y > 10 ? 'shadow-lg shadow-brand-700/10' : ''">
        <div class="mx-auto flex w-full max-w-[1700px] items-center justify-between gap-4 px-4 transition-all duration-300 sm:px-6 2xl:px-8" :class="y > 40 ? 'h-16' : 'h-20'">
            <a href="{{ route('home') }}" class="group" aria-label="{{ setting('site_name') }} – home"><span class="md:hidden"><x-logo size="sm" :tagline="false" /></span><span class="hidden md:block"><x-logo size="sm" /></span></a>

            <nav class="hidden items-center gap-0.5 xl:flex" aria-label="Main">
                @foreach($links as [$label, $url, $active, $icon])
                    @if($label === 'Blogs')
                        <div class="relative" @mouseenter="blog = true" @mouseleave="blog = false">
                            <a href="{{ $url }}" @if($active) aria-current="page" @endif class="nav-link" aria-haspopup="true" :aria-expanded="blog"><x-icon :name="$icon" class="h-4 w-4 opacity-80" />{{ $label }}
                                <svg class="h-3 w-3 transition" :class="blog && 'rotate-180'" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m2.5 4.5 3.5 3.5 3.5-3.5"/></svg></a>
                            <div x-show="blog" x-cloak x-transition.opacity.duration.150ms class="absolute left-1/2 top-full w-80 -translate-x-1/2 pt-3">
                                <div class="rounded-3xl bg-card p-3 shadow-2xl ring-1 ring-lavender-200">
                                    @foreach($blogMenu as [$t, $u, $i, $d])
                                        <a href="{{ $u }}" class="flex items-start gap-3 rounded-2xl p-3 transition hover:bg-lavender-50">
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand-400 to-copper text-white"><x-icon :name="$i" class="h-5 w-5" /></span>
                                            <span><span class="block text-sm font-semibold text-lilac">{{ $t }}</span><span class="block text-xs text-mute">{{ $d }}</span></span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $url }}" @if($active) aria-current="page" @endif class="nav-link"><x-icon :name="$icon" class="h-4 w-4 opacity-80" />{{ $label }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <button type="button" @click="find = !find; $nextTick(() => $refs.q && $refs.q.focus())" class="rounded-full bg-card/70 p-2.5 text-lilac transition hover:bg-card" aria-label="Search articles" :aria-expanded="find"><x-icon name="search" class="h-5 w-5" /></button>
                <a href="tel:{{ $tel }}" class="btn btn-primary hidden whitespace-nowrap !py-2.5 xl:inline-flex"><x-icon name="phone" class="h-4 w-4" /> Call us</a>
                <button type="button" class="rounded-full bg-card/70 p-2.5 text-lilac xl:hidden" @click="open = !open" :aria-expanded="open" aria-controls="mobile-nav" aria-label="Toggle menu">
                    <x-icon name="menu" class="h-5 w-5" x-show="!open" />
                    <x-icon name="close" class="h-5 w-5" x-show="open" x-cloak />
                </button>
            </div>
        </div>

        <div x-show="find" x-cloak x-transition.opacity class="border-t border-white/50 bg-card/90">
            <form action="{{ route('blogs.index') }}" method="get" role="search" class="container-x flex items-center gap-3 py-3">
                <x-icon name="search" class="h-5 w-5 text-lilac" />
                <input x-ref="q" type="search" name="q" placeholder="Search articles, e.g. PCOS, first trimester, menopause…" class="w-full bg-transparent py-2 text-base text-ink outline-none placeholder:text-mute/70" aria-label="Search articles">
                <button class="btn btn-primary !py-2">Search</button>
            </form>
        </div>

    </header>

        <div x-show="open" x-cloak class="fixed inset-0 top-0 z-40 xl:hidden" aria-hidden="false">
            <div class="absolute inset-0 bg-brand-900/50 backdrop-blur-sm" @click="open = false"></div>
            <nav id="mobile-nav" x-show="open" x-transition:enter="transition duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                 class="absolute right-0 top-0 flex h-full w-[85%] max-w-sm flex-col overflow-y-auto bg-card p-5 shadow-2xl" aria-label="Mobile">
                <div class="mb-4 flex items-center justify-between">
                    <span class="font-display text-xl font-semibold text-lilac">Menu</span>
                    <button type="button" @click="open = false" class="rounded-full bg-lavender-100 p-2 text-lilac" aria-label="Close menu"><x-icon name="close" class="h-5 w-5" /></button>
                </div>
                <div class="flex flex-col gap-1">
                    @foreach($links as [$label, $url, $active, $icon])
                        <a href="{{ $url }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-base font-medium {{ $active ? 'bg-gradient-to-r from-brand-600 to-copper text-white' : 'text-ink hover:bg-lavender-50' }}">
                            <x-icon :name="$icon" class="h-5 w-5 {{ $active ? '' : 'text-lilac' }}" /> {{ $label }}</a>
                    @endforeach
                    <a href="{{ $weekPost }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-base font-medium text-ink hover:bg-lavender-50"><x-icon name="baby" class="h-5 w-5 text-lilac" /> Pregnancy week by week</a>
                </div>
                <div class="mt-auto grid gap-2 pt-6">
                    <a href="tel:{{ $tel }}" class="btn btn-primary"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
                    @if(setting('whatsapp'))<a href="https://wa.me/{{ preg_replace('/\D/', '', setting('whatsapp')) }}" target="_blank" rel="noopener" class="btn btn-soft"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp us</a>@endif
                </div>
            </nav>
        </div>

    <button type="button" x-show="y > 600" x-cloak x-transition.opacity @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-brand-600 to-copper text-white shadow-lg shadow-brand-700/30 transition hover:-translate-y-1 print:hidden" aria-label="Back to top">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 14 6-6 6 6"/></svg>
    </button>
</div>
