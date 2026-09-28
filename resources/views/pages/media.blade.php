@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Media & Press</span><h1>Ricevibe in the news</h1></div></section>
<section class="section"><div class="container media-preview-grid">@foreach($site['media'] ?? [] as $item)<article class="story-card"><div class="story-image-frame"><img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" /></div><div class="card-copy"><span class="eyebrow">{{ $item['source'] }} · {{ $item['date'] }}</span><h2>{{ $item['title'] }}</h2><p>{{ $item['excerpt'] }}</p></div></article>@endforeach</div></section>
<section class="section alt-bg"><div class="container video-grid">@foreach($site['videos'] ?? [] as $video)<article class="video-card"><a href="{{ $video['url'] }}" target="_blank" rel="noreferrer"><img src="{{ $video['thumb'] }}" alt="{{ $video['title'] }}" loading="lazy" /><h2>{{ $video['title'] }}</h2></a></article>@endforeach</div></section>
@endsection