@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Product assurance</span><h1>Certificates and compliance</h1><p>Certification information is available from Ricevibe on request.</p></div></section>
<section class="section"><div class="container cert-grid">@foreach($site['certificates'] ?? [] as $item)<article class="cert-card"><img src="{{ $item['thumb'] ?? $item['image'] ?? '' }}" alt="{{ $item['title'] ?? 'Ricevibe certificate' }}" loading="lazy" /><div class="card-copy"><h2>{{ $item['title'] ?? 'Certificate' }}</h2><a href="{{ $item['file'] ?? '#' }}" target="_blank" rel="noreferrer">View certificate</a></div></article>@endforeach</div></section>
@endsection