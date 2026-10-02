@extends('layouts.app')

@section('content')
<section class="hero-section"><div class="hero-carousel" data-carousel>
  @forelse($site['hero'] as $slide)
  <article class="hero-slide {{ $loop->first ? 'active' : '' }}"><a class="hero-slide-link" href="{{ $slide->button_link ?: '/shop' }}" aria-label="{{ $slide->title }}"><img src="{{ str_starts_with($slide->image_path, '/') ? $slide->image_path : \Illuminate\Support\Facades\Storage::url($slide->image_path) }}" alt="{{ $slide->title }}" @if(!$loop->first) loading="lazy" @endif /></a></article>
  @empty
  <div class="hero-empty">Ricevibe biodegradable products for better everyday service.</div>
  @endforelse
</div></section>
<section class="proof-strip"><div class="container proof-grid"><div><strong>01</strong><span>Natural materials</span></div><div><strong>02</strong><span>Chennai-based supply</span></div><div><strong>03</strong><span>Retail and bulk orders</span></div><div><strong>04</strong><span>Made for better sipping</span></div></div></section>
<section class="section"><div class="container"><div class="section-heading center"><span class="eyebrow">Browse categories</span><h2>{{ $site['homepage']['categories_heading'] ?? 'Eco-friendly essentials' }}</h2></div><div class="category-grid reveal-on-scroll">
  @foreach($site['categories'] as $category)
  @php
    $categoryImage = match ($category->slug) {
      'garnish-picks' => '/static/assets/ricevibe/garnish-1.jpg',
      'wooden-stirrers' => '/static/assets/cat-stirrers.svg',
      default => $category->image_path ?? '',
    };
  @endphp
  <article class="category-card"><img src="{{ str_starts_with($categoryImage, '/') ? $categoryImage : \Illuminate\Support\Facades\Storage::url($categoryImage) }}" alt="{{ $category->name }}" loading="lazy" /><div class="card-copy"><h3>{{ $category->name }}</h3><p>{{ $category->description }}</p><a href="/shop?category={{ $category->slug }}">Explore <span aria-hidden="true">→</span></a></div></article>
  @endforeach
</div></div></section>
<section class="section"><div class="container"><div class="section-heading space-between"><div><span class="eyebrow">Featured range</span><h2>{{ $site['homepage']['products_heading'] ?? 'Popular products' }}</h2></div><a href="/shop" class="text-link">View all products →</a></div><div class="product-grid reveal-on-scroll">
  @foreach($site['products']->take(4) as $product)
  <article class="product-card"><div class="product-image-wrap"><a href="/shop/{{ $product->slug }}"><img src="{{ str_starts_with($product->image_path ?? '', '/') ? $product->image_path : \Illuminate\Support\Facades\Storage::url($product->image_path ?? '') }}" alt="{{ $product->name }}" loading="lazy" /></a>@if($product->badge)<span class="badge">{{ $product->badge }}</span>@endif</div><div class="card-copy"><h3><a href="/shop/{{ $product->slug }}">{{ $product->name }}</a></h3><p>{{ $product->description }}</p><div class="product-detail-points"><span>{{ $product->pack }}</span><span>{{ $product->specs[0] ?? 'Natural material' }}</span></div><a class="btn enquiry-button" href="/contact?product={{ urlencode($product->name) }}">Enquire now <span aria-hidden="true">→</span></a></div></article>
  @endforeach
</div></div></section>
<section class="section alt-bg"><div class="container"><div class="section-heading center"><span class="eyebrow">Why Ricevibe</span><h2>{{ $site['homepage']['why_heading'] ?? 'Purpose-built for sustainable hospitality' }}</h2></div><div class="feature-grid">
  @foreach($site['why_ricevibe'] ?? [] as $item)<article class="feature-card"><div class="feature-icon icon-{{ $item['icon'] ?? 'leaf' }}" aria-hidden="true"></div><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></article>@endforeach
</div></div></section>
<section class="section sustainability-section"><div class="container sustainability-grid"><div><span class="eyebrow">Sustainability</span><h2>{{ $site['homepage']['sustainability_heading'] ?? 'Made from Nature. Made for a Better Tomorrow.' }}</h2><p>Thoughtful materials and responsible product choices for the businesses shaping a cleaner everyday.</p><ul class="check-list"><li>Natural, food-friendly materials</li><li>Suitable for hospitality and retail</li><li>Designed for premium presentation</li></ul></div><div class="sustainability-image"><img src="/static/assets/ricevibe/rice-straw-main.jpg" alt="Natural Ricevibe rice straws" loading="lazy" /></div></div></section>
<section class="cta-strip bulk-orders"><div class="container cta-strip-inner"><div><span class="eyebrow">Bulk supply</span><h2>{{ $site['homepage']['bulk_heading'] ?? 'Better choices for your business' }}</h2><p>Hotels · Restaurants · Cafes · Catering · Retail · Distributors</p></div><div class="cta-actions"><a class="btn primary" href="/contact">Enquire Now</a><a class="btn secondary" href="{{ $site['brand']['whatsapp'] }}" target="_blank" rel="noreferrer">WhatsApp</a></div></div></section>
<section class="section gallery-preview"><div class="container"><div class="section-heading space-between"><div><span class="eyebrow">Gallery</span><h2>Made to be seen in every detail</h2></div><a href="/gallery" class="text-link">View Gallery →</a></div><div class="gallery-preview-grid">@foreach(array_slice($site['gallery'] ?? [], 0, 6) as $item)<a href="/gallery" class="gallery-preview-item"><img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" /></a>@endforeach</div></div></section>
<section class="section testimonial-section"><div class="container"><div class="section-heading space-between"><div><span class="eyebrow">Client feedback</span><h2>{{ $site['homepage']['feedback_heading'] ?? 'Good straws. Happier service.' }}</h2></div><div class="carousel-controls"><button class="carousel-button testimonial-prev" type="button" aria-label="Previous feedback">←</button><button class="carousel-button testimonial-next" type="button" aria-label="Next feedback">→</button></div></div><div class="testimonial-carousel">@foreach($site['testimonials'] ?? [] as $item)<article class="testimonial-card {{ $loop->first ? 'active' : '' }}"><div class="quote-mark" aria-hidden="true">“</div><p>{{ $item['quote'] }}</p><footer><strong>{{ $item['name'] }}</strong><span>{{ $item['role'] }}</span></footer></article>@endforeach</div></div></section>
@endsection