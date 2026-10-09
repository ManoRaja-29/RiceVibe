@extends('layouts.app')

@section('content')
<x-page-header title="Consult Q&A" eyebrow="Ask the doctor" intro="Questions from patients, answered by Dr. Indhra. General educational information only — it does not replace an in-person medical assessment." :crumbs="['Consult Q&A' => null]">
    <form action="{{ route('qa.index') }}" method="GET" role="search" class="mt-8 flex max-w-xl items-center gap-2 rounded-full bg-card p-1.5 ring-1 ring-sky-pastel focus-within:ring-2 focus-within:ring-brand-500">
        @if($topic)<input type="hidden" name="topic" value="{{ $topic }}">@endif
        <label for="q" class="sr-only">Search questions</label>
        <x-icon name="search" class="ml-3 h-5 w-5 shrink-0 text-mute" />
        <input id="q" name="q" type="search" value="{{ $term }}" placeholder="Search questions…" class="w-full border-0 bg-transparent px-2 py-2 text-sm focus:ring-0">
        <button class="btn btn-primary !py-2">Search</button>
    </form>
</x-page-header>

<section class="section !pt-10">
    <div class="container-x">
        <div class="flex flex-wrap items-center gap-2" aria-label="Filter by topic">
            <a href="{{ route('qa.index') }}" class="chip {{ !$topic ? 'chip-active' : '' }}">All</a>
            @foreach(\App\Models\Question::TOPICS as $t)
                <a href="{{ route('qa.index', ['topic' => $t]) }}" class="chip {{ $topic === $t ? 'chip-active' : '' }}">{{ $t }}</a>
            @endforeach
            <a href="{{ route('qa.ask') }}" class="btn btn-primary ml-auto !py-2">Ask a question</a>
        </div>

        @if($term)<p class="mt-6 text-sm text-mute">{{ $questions->total() }} result(s) for “{{ $term }}” <a href="{{ route('qa.index') }}" class="ml-2 text-lilac underline">Clear</a></p>@endif

        <div class="mt-8 grid gap-5 lg:grid-cols-2">
            @forelse($questions as $q)
                <a href="{{ route('qa.show', $q->id) }}" class="card group block">
                    @if($q->topic)<span class="chip">{{ $q->topic }}</span>@endif
                    <h2 class="mt-3 font-sans text-lg font-semibold text-ink group-hover:text-lilac">{{ $q->title }}</h2>
                    <p class="mt-2 text-sm text-mute">{{ \Illuminate\Support\Str::limit($q->body, 150) }}</p>
                    <p class="mt-4 text-sm text-ink"><span class="font-medium text-lilac">Dr. Indhra:</span> {{ \Illuminate\Support\Str::limit($q->answer, 140) }}</p>
                    <p class="mt-4 text-xs text-mute">Asked by {{ $q->asker_first_name }} · {{ $q->answered_at?->format('d M Y') }}</p>
                </a>
            @empty
                <p class="card text-center text-mute lg:col-span-2">No answered questions yet. Be the first to <a class="text-lilac underline" href="{{ route('qa.ask') }}">ask a question</a>.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $questions->links() }}</div>
    </div>
</section>

@if($faqs->isNotEmpty())
<section class="section bg-ice-50">
    <div class="container-x grid gap-10 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <span class="eyebrow">FAQs</span>
            <h2 class="h-section">Common questions</h2>
            <p class="lead mt-4">Quick answers to things patients often ask. For personal advice, please get in touch with the clinic.</p>
        </div>
        <div class="lg:col-span-8"><x-faq-list :faqs="$faqs" /></div>
    </div>
</section>
@endif
@endsection
