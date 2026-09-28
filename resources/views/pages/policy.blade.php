@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Ricevibe policies</span><h1>{{ $pageTitle }}</h1></div></section>
<section class="section"><div class="container policy-copy"><p>{{ $site['policies'][$policyKey] ?? 'Please contact Ricevibe for policy details.' }}</p></div></section>
@endsection