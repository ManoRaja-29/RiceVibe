@extends('layouts.app')

@section('content')
<x-page-header :title="$question->title" eyebrow="Consult Q&A" :crumbs="['Consult Q&A' => route('qa.index'), 'Answer' => null]" />

<section class="section !pt-10">
    <div class="container-x max-w-3xl space-y-6">
        <div class="card">
            <p class="text-xs uppercase tracking-widest text-mute">Question · {{ $question->asker_first_name }}@if($question->topic) · {{ $question->topic }}@endif</p>
            <p class="mt-3 whitespace-pre-line text-ink">{{ $question->body }}</p>
        </div>
        <div class="card border-0 bg-ice-50">
            <p class="text-xs uppercase tracking-widest text-lilac">Answer by {{ setting('doctor_name') }} · {{ $question->answered_at?->format('d M Y') }}</p>
            <p class="mt-3 whitespace-pre-line text-ink">{{ $question->answer }}</p>
        </div>
        <p class="rounded-2xl bg-peach-50 p-4 text-sm text-mute ring-1 ring-peach-100">This reply is general information based on the details shared and is not a diagnosis. For urgent symptoms, go to your nearest hospital.</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('qa.index') }}" class="btn btn-ghost">← All questions</a>
            <a href="{{ route('qa.ask') }}" class="btn btn-primary">Ask a question</a>
        </div>
    </div>
</section>
@endsection
