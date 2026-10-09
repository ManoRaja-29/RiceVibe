@extends('layouts.app')

@section('content')
<x-page-header :title="$service->title" :eyebrow="$page['title']" :intro="$service->summary" :crumbs="[$page['title'] => route($section), $service->title => null]" />

<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <article class="lg:col-span-8">
            @if($service->image)<img src="{{ media_url($service->image) }}" alt="{{ $service->title }}" class="mb-8 aspect-[16/9] w-full rounded-3xl object-cover">@endif
            <div class="rich">{!! $service->body !!}</div>
            <p class="mt-10 rounded-2xl bg-ice-50 p-5 text-sm text-mute ring-1 ring-ice-200">This page offers general education only. It does not replace an assessment by a doctor — every person’s situation is different.</p>
        </article>
        <aside class="space-y-6 lg:col-span-4">
            <div class="card bg-gradient-to-br from-ice-100 to-lavender-50">
                <h2 class="text-2xl">Discuss this with Dr. Indhra</h2>
                <p class="mt-2 text-sm text-mute">Send an enquiry to talk through your own situation.</p>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-5 w-full">Contact us</a>
            </div>
            @if($others->isNotEmpty())
                <div class="card">
                    <h2 class="text-2xl">More in {{ strtolower($page['title']) }}</h2>
                    <ul class="mt-4 space-y-3 text-sm">
                        @foreach($others as $o)<li><a class="flex items-center gap-2 text-ink hover:text-lilac" href="{{ route($section.'.show', $o->slug) }}"><x-icon name="arrow" class="h-4 w-4 text-brand-400" />{{ $o->title }}</a></li>@endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</section>
@endsection
