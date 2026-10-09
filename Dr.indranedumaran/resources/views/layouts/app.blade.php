<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php $seo = $seo ?? \App\Support\Seo::make(); @endphp
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($seo['description']), 160) }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta name="robots" content="{{ $seo['noindex'] ? 'noindex,follow' : 'index,follow,max-image-preview:large' }}">
    @if(setting('google_site_verification'))<meta name="google-site-verification" content="{{ setting('google_site_verification') }}">@endif
    <meta property="og:site_name" content="{{ setting('site_name') }}">
    <meta property="og:type" content="{{ $seo['type'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($seo['description']), 200) }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:image" content="{{ $seo['image'] }}">
    <meta property="og:locale" content="en_IN">
    @if(!empty($seo['extra']['published']))<meta property="article:published_time" content="{{ $seo['extra']['published']->toIso8601String() }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#7a3fc2">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600,700|inter:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @foreach($seo['schema'] as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endforeach
    @if(setting('ga_measurement_id') && preg_match('/^G-[A-Z0-9]+$/', setting('ga_measurement_id')))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('ga_measurement_id') }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','{{ setting('ga_measurement_id') }}');</script>
    @endif
</head>
<body>
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-card focus:px-4 focus:py-2 focus:shadow">Skip to content</a>

@include('partials.nav')

<main id="main">
    @yield('content')
</main>

@include('partials.footer')
</body>
</html>
