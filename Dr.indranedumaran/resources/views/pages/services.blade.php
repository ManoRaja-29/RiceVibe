@extends('layouts.app')

@section('content')
<x-page-header :title="$page['title']" eyebrow="Services" :intro="$page['intro']" :crumbs="[$page['title'] => null]" />

<section class="section">
    <div class="container-x">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $s)
                <a href="{{ route($section.'.show', $s->slug) }}" class="card group flex flex-col">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-ice-100 text-lilac"><x-icon :name="$s->icon ?: 'heart'" class="h-6 w-6" /></span>
                    <h2 class="mt-5 text-2xl transition group-hover:text-lilac">{{ $s->title }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-mute">{{ $s->summary }}</p>
                    <span class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-lilac">Learn more <x-icon name="arrow" class="h-4 w-4" /></span>
                </a>
            @empty
                <p class="text-mute">Content for this section is being prepared.</p>
            @endforelse
        </div>

        @if($section === 'gynaecology')
            <div class="mt-20"><x-pregnancy-calculator /></div>
        @endif

        @if($section === 'maternity')
            <div class="mt-20"><x-trimester-chart /></div>
        @endif

        @if($stages->isNotEmpty())
            <div class="mt-20">
                <h2 class="h-section">Pregnancy, stage by stage</h2>
                <div class="mt-8 grid gap-5 md:grid-cols-2">
                    @foreach($stages as $stage)
                        <div class="rounded-3xl bg-ice-50 p-6 ring-1 ring-ice-200">
                            @if($stage->label)<p class="text-xs font-semibold uppercase tracking-widest text-copper">{{ $stage->label }}</p>@endif
                            <h3 class="mt-1 text-2xl">{{ $stage->title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-mute">{{ $stage->summary }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($faqs->isNotEmpty())
            <div class="mt-20 grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-4"><span class="eyebrow">FAQs</span><h2 class="h-section">Questions, answered</h2></div>
                <div class="lg:col-span-8"><x-faq-list :faqs="$faqs" /></div>
            </div>
        @endif

        <div class="mt-20 rounded-[2.5rem] bg-gradient-to-br from-ice-100 via-lavender-50 to-peach-50 p-10 text-center ring-1 ring-ice-200">
            <h2 class="text-4xl">Have a question about {{ strtolower($page['title']) }}?</h2>
            <p class="lead mx-auto mt-3 max-w-xl">Send us an enquiry and our team will get back to you.</p>
            <a href="{{ route('contact') }}" class="btn btn-primary mt-6">Contact us</a>
        </div>
    </div>
</section>
@endsection
