@extends('layouts.app')

@section('content')
<x-page-header title="Patient stories" eyebrow="Stories" intro="Experiences shared by patients. Every person’s care is individual, and these stories are not a guarantee of any outcome." :crumbs="['Stories' => null]" />

<section class="section !pt-10">
    <div class="container-x">
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($stories as $s)
                @include('partials.story-card', ['s' => $s])
            @empty
                <p class="card text-center text-mute md:col-span-2 lg:col-span-3">Stories will appear here soon.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $stories->links() }}</div>
    </div>
</section>
@endsection
