@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Support</span><h1>Frequently asked questions</h1></div></section>
<section class="section"><div class="container faq-list">@foreach($site['faq'] ?? [] as $item)<details class="faq-item"><summary class="faq-question">{{ $item['question'] }}</summary><p>{{ $item['answer'] }}</p></details>@endforeach</div></section>
@endsection