@extends('layouts.app')

@section('content')
<x-page-header title="Ask a question" eyebrow="Consult Q&A" intro="Send your question to Dr. Indhra. Replies are published on the Q&A page after review; your email and surname are never shown." :crumbs="['Consult Q&A' => route('qa.index'), 'Ask' => null]" />

<section class="section !pt-10">
    <div class="container-x max-w-3xl">
        @if(session('sent'))
            <div class="card border-0 bg-ice-50 text-center" role="status">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-card text-lilac"><x-icon name="check" class="h-7 w-7" /></span>
                <h2 class="mt-4 text-3xl">Thank you — your question has been received</h2>
                <p class="mt-2 text-sm text-mute">It will appear on the Q&amp;A page once the doctor has replied.</p>
                <a href="{{ route('qa.index') }}" class="btn btn-primary mt-6">Browse answered questions</a>
            </div>
        @else
            <form method="POST" action="{{ route('qa.store') }}" class="card space-y-5" novalidate>
                @csrf
                <input type="hidden" name="_ft" value="{{ $formTime }}">
                <div class="hidden" aria-hidden="true"><label>Leave empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label class="label" for="asker_name">Your name *</label><input class="input" id="asker_name" name="asker_name" value="{{ old('asker_name') }}" required autocomplete="name">@error('asker_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="label" for="asker_email">Email (not shown publicly) *</label><input class="input" id="asker_email" type="email" name="asker_email" value="{{ old('asker_email') }}" required autocomplete="email">@error('asker_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label class="label" for="topic">Topic</label>
                        <select class="input" id="topic" name="topic"><option value="">Select a topic (optional)</option>@foreach(\App\Models\Question::TOPICS as $t)<option @selected(old('topic') === $t)>{{ $t }}</option>@endforeach</select>
                    </div>
                    <div class="sm:col-span-2"><label class="label" for="title">Question in one line *</label><input class="input" id="title" name="title" value="{{ old('title') }}" required maxlength="200">@error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                </div>
                <div><label class="label" for="body">Details *</label><textarea class="input" id="body" name="body" rows="6" required minlength="20">{{ old('body') }}</textarea>@error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div>
                    <label class="flex items-start gap-3 text-sm text-mute"><input type="checkbox" name="consent" value="1" class="mt-1 rounded text-lilac focus:ring-brand-500" @checked(old('consent'))> <span>I understand my question and the reply may be published (first name only) and that the answer is general information, not a diagnosis.</span></label>
                    @error('consent')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <p class="rounded-2xl bg-peach-50 p-4 text-sm text-mute ring-1 ring-peach-100">For urgent symptoms such as heavy bleeding, severe pain or reduced baby movements, go to your nearest hospital — do not wait for a reply here.</p>
                <button class="btn btn-primary w-full sm:w-auto" type="submit">Submit question</button>
            </form>
        @endif
    </div>
</section>
@endsection
