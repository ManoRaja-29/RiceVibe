@extends('admin.layout')
@section('title', ($record->exists ? 'Edit ' : 'New ').$singular)

@section('content')
@php $groups = collect($fields)->groupBy(fn ($f) => $f['section'] ?? 'Content'); @endphp
<form method="POST" enctype="multipart/form-data"
      action="{{ $record->exists ? route($route.'.update', $record->getKey()) : route($route.'.store') }}" class="mx-auto max-w-4xl space-y-6">
    @csrf
    @if($record->exists)@method('PUT')@endif

    @foreach($groups as $section => $items)
        <section class="rounded-2xl bg-card p-6 ring-1 ring-slate-200">
            @if($groups->count() > 1)<h2 class="mb-5 font-sans text-sm font-semibold uppercase tracking-wide text-slate-500">{{ $section }}</h2>@endif
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach($items as $f)
                    @php $wide = in_array($f['type'] ?? 'text', ['textarea', 'richtext', 'image', 'images']) || in_array($f['name'], ['title', 'slug', 'question', 'name', 'caption', 'meta_title']); @endphp
                    <div class="{{ $wide ? 'sm:col-span-2' : '' }}">@include('admin.partials.field', ['f' => $f, 'record' => $record])</div>
                @endforeach
            </div>
        </section>
    @endforeach

    <div class="flex items-center justify-between">
        <a href="{{ route($route.'.index') }}" class="a-btn a-btn-ghost">← Back</a>
        <button class="a-btn a-btn-primary">{{ $record->exists ? 'Save changes' : 'Create' }}</button>
    </div>
</form>
@endsection
