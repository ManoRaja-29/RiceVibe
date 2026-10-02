@extends('layouts.app')
@section('content')
<section class="page-hero compact">
	<div class="container">
		<span class="eyebrow">Brochure</span>
		<h1>Quality Products. Strong Partnerships. Customer Focus.</h1>
		<p>Ricevibe Enterprises Private Limited connects quality Food &amp; Beverage, hospitality and sustainable products with businesses across India.</p>
		<a class="btn primary" href="{{ route('brochure.download') }}">Download brochure PDF</a>
	</div>
</section>

<section class="section">
	<div class="container brochure-grid">
		<article class="info-panel"><span class="eyebrow">What we do</span><h2>Products that help your business grow.</h2><p>We bring carefully selected products to hotels, restaurants, cafes, retailers, distributors and businesses across India.</p><ul class="check-list"><li>Food &amp; Beverages</li><li>Premium Syrups</li><li>Hospitality Products</li><li>Sustainable Products</li><li>Distribution</li></ul></article>
		<article class="info-panel"><span class="eyebrow">Our story</span><h2>From sustainable straws to a growing portfolio.</h2><p>Ricevibe started with biodegradable rice straws, wooden stirrers, chopsticks, garnish picks and other hospitality essentials. Today, the portfolio continues to expand across Food &amp; Beverage, hospitality and distribution.</p><p>We are also a distribution partner for Saksham Impex Private Limited, bringing its food and beverage products to the Indian market.</p></article>
	</div>
</section>

<section class="section alt-bg brochure-document">
	<div class="container">
		<div class="section-heading space-between"><div><span class="eyebrow">Official document</span><h2>Ricevibe company brochure</h2></div><a class="text-link" href="{{ route('brochure.download') }}">Download PDF →</a></div>
		<div class="brochure-frame"><iframe src="/static/assets/ricevibe/jp_brochure.pdf" title="Ricevibe official brochure" loading="lazy"></iframe></div>
	</div>
</section>
@endsection