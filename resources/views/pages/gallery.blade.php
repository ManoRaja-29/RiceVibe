@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Gallery</span><h1>Product moments and hospitality in action</h1></div></section>
<section class="section">
  <div class="container gallery-browser" data-gallery-browser>
    <div class="gallery-filters" role="tablist" aria-label="Gallery categories">
      <button class="gallery-filter active" type="button" data-gallery-filter="all">All</button>
      @foreach($site['categories'] as $category)
        <button class="gallery-filter" type="button" data-gallery-filter="{{ $category->name }}">{{ $category->name }}</button>
      @endforeach
    </div>

    <div class="gallery-grid">
      @foreach(($site['products'] ?? collect()) as $product)
        @php
          $categoryName = $product->category?->name ?? 'Products';
          $images = collect([$product->image_path])->merge($product->gallery ?? [])->filter()->unique()->values();
        @endphp
        @foreach($images as $image)
          <figure class="gallery-item" data-gallery-category="{{ $categoryName }}">
            <div class="gallery-image-frame">
              <img src="{{ str_starts_with($image, '/') ? $image : \Illuminate\Support\Facades\Storage::url($image) }}" alt="{{ $product->name }}" loading="lazy" />
            </div>
            <figcaption><span>{{ $categoryName }}</span> {{ $product->name }}</figcaption>
          </figure>
        @endforeach
      @endforeach

      @foreach($site['gallery'] ?? [] as $item)
        <figure class="gallery-item" data-gallery-category="{{ $item['category'] }}">
          <div class="gallery-image-frame"><img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" /></div>
          <figcaption><span>{{ $item['category'] }}</span> {{ $item['title'] }}</figcaption>
        </figure>
      @endforeach
    </div>
  </div>
</section>
@endsection
