@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Brochure</span><h1>Ricevibe product and company overview</h1><p>Discover natural, sustainable essentials for beverage and hospitality service.</p><a class="btn primary" href="{{ route('brochure.download') }}">Download brochure</a></div></section>
<section class="section"><div class="container brochure-grid"><iframe title="Ricevibe brochure" src="{{ route('brochure.view') }}" width="100%" height="720"></iframe></div></section>
@endsection