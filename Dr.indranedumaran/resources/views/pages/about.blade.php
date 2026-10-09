@extends('layouts.app')

@section('content')
@php $photo = media_url(setting('about_photo')) ?: (media_url(setting('doctor_photo')) ?: asset('images/doctor.webp')); @endphp
<x-page-header :title="setting('about_headline')" eyebrow="About" :intro="setting('doctor_title')" :crumbs="['About' => null]" />

<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <div class="sticky top-28">
                <div class="aspect-[4/5] overflow-hidden rounded-t-[999px] rounded-b-[2rem] bg-gradient-to-b from-sky-pastel/70 via-ice-100 to-lavender-100 ring-1 ring-ice-200">
                    <img src="{{ $photo }}" alt="{{ setting('doctor_photo_alt') }}" width="524" height="634" class="h-full w-full object-cover object-top">
                </div>
                @if(setting('about_philosophy'))
                    <blockquote class="mt-6 rounded-3xl bg-peach-50 p-6 font-display text-xl italic leading-snug text-heading ring-1 ring-peach-100">“{{ setting('about_philosophy') }}”</blockquote>
                @endif
            </div>
        </div>

        <div class="lg:col-span-7">
            <div class="space-y-5 text-lg leading-8 text-ink">
                @foreach(preg_split('/\R{2,}/', trim(setting('about_bio', ''))) as $para)<p>{{ $para }}</p>@endforeach
            </div>

            @php $stats = array_filter([[setting('stat_experience'), setting('stat_experience_label')], [setting('stat_2'), setting('stat_2_label')], [setting('stat_3'), setting('stat_3_label')]], fn ($s) => $s[0]); @endphp
            @if($stats)
                <dl class="mt-10 grid gap-4 sm:grid-cols-3">
                    @foreach($stats as [$v, $l])
                        <div class="rounded-3xl bg-ice-50 p-6 text-center ring-1 ring-ice-200"><dt class="order-2 mt-1 text-xs uppercase tracking-widest text-mute">{{ $l }}</dt><dd class="font-display text-4xl text-lilac">{{ $v }}</dd></div>
                    @endforeach
                </dl>
            @endif

            @foreach(['qualification' => ['Qualifications', 'shield'], 'membership' => ['Memberships & societies', 'leaf'], 'registration' => ['Registrations', 'shield'], 'achievement' => ['Achievements', 'sparkle']] as $type => [$heading, $icon])
                @if(!empty($items[$type]) && $items[$type]->isNotEmpty())
                    <div class="mt-14">
                        <h2 class="flex items-center gap-3 text-3xl"><x-icon :name="$icon" class="h-6 w-6 text-lilac" /> {{ $heading }}</h2>
                        <ul class="mt-6 space-y-4">
                            @foreach($items[$type] as $it)
                                <li class="card !p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="font-sans text-base font-semibold text-heading">{{ $it->title }}</h3>
                                        <span class="flex items-center gap-2 text-xs text-mute">{{ $it->period }} @if($it->is_placeholder)<span class="placeholder-badge">Placeholder</span>@endif</span>
                                    </div>
                                    @if($it->subtitle)<p class="mt-1 text-sm text-mute">{{ $it->subtitle }}</p>@endif
                                    @if($it->description)<p class="mt-2 text-sm leading-relaxed text-mute">{{ $it->description }}</p>@endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach

            @if(!empty($items['timeline']) && $items['timeline']->isNotEmpty())
                <div class="mt-14">
                    <h2 class="text-3xl">Career timeline</h2>
                    <ol class="relative mt-8 space-y-8 border-l border-sky-pastel pl-8">
                        @foreach($items['timeline'] as $it)
                            <li class="relative">
                                <span class="absolute -left-[2.55rem] top-1.5 h-4 w-4 rounded-full border-4 border-white bg-brand-500 ring-1 ring-sky-pastel"></span>
                                <p class="text-xs font-semibold uppercase tracking-widest text-copper">{{ $it->period }} @if($it->is_placeholder)<span class="placeholder-badge ml-2">Placeholder</span>@endif</p>
                                <h3 class="mt-1 font-sans text-lg font-semibold text-heading">{{ $it->title }}</h3>
                                @if($it->subtitle)<p class="text-sm text-mute">{{ $it->subtitle }}</p>@endif
                                @if($it->description)<p class="mt-2 text-sm leading-relaxed text-mute">{{ $it->description }}</p>@endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            <div class="mt-14 flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="btn btn-primary">Contact us</a>
                <a href="{{ route('activities.index') }}" class="btn btn-soft">Conferences &amp; activities</a>
            </div>
        </div>
    </div>
</section>
@endsection
