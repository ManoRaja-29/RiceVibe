<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $pageTitle ?? $site['brand']['name'] ?? 'Ricevibe' }}</title>
  <meta name="description" content="{{ $metaDescription ?? $site['brand']['tagline'] ?? '' }}" />
  <meta name="robots" content="index,follow" />
  <link rel="canonical" href="{{ url(request()->path() === '/' ? '/' : '/'.request()->path()) }}" />
  <meta property="og:title" content="{{ $pageTitle ?? 'Ricevibe' }}" />
  <meta property="og:description" content="{{ $metaDescription ?? '' }}" />
  <link rel="icon" type="image/png" href="/static/assets/ricevibe/logo-final.png" />
  <link rel="stylesheet" href="/static/css/style.css" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="main-navigation"><span></span><span></span><span></span></button>
      <a href="/" class="brand" aria-label="Ricevibe home"><img src="/static/assets/ricevibe/logo-cropped.png" alt="Ricevibe - biodegradable rice straws" /></a>
      <nav id="main-navigation" class="main-nav" aria-label="Main navigation">
        <button class="nav-close" type="button" aria-label="Close navigation">×</button>
        <ul>
          @foreach(($site['nav'] ?? []) as $item)
          @if(strtolower($item['label'] ?? '') !== 'certificates')<li><a href="{{ $item['url'] }}" @class(['active' => request()->path() === trim($item['url'], '/')])>{{ $item['label'] }}</a></li>@endif
          @endforeach
        </ul>
      </nav>
      <button class="menu-backdrop" type="button" aria-label="Close navigation"></button>
      <div class="header-tools">
        <form class="header-search" action="/shop" method="get"><label class="sr-only" for="site-search">Search products</label><input id="site-search" type="search" name="q" placeholder="Search" /></form>
        <a href="/contact" class="cart-link enquiry-link" aria-label="Send an enquiry"><span>Enquire</span></a>
      </div>
    </div>
  </header>
  <main>@yield('content')</main>
  <footer class="site-footer">
    <div class="container footer-grid">
      <div><h3>About</h3><p>Ricevibe offers eco-friendly, food-grade rice straws, wooden stirrers and garnish picks for hospitality and retail.</p></div>
      <div><h3>Quick links</h3><ul><li><a href="/shop">Shop</a></li><li><a href="/gallery">Gallery</a></li><li><a href="/brochure">Brochure</a></li></ul></div>
      <div><h3>Support</h3><ul><li><a href="/contact">Contact</a></li><li><a href="/privacy-policy">Privacy</a></li><li><a href="/terms-and-conditions">Terms</a></li></ul></div>
      <div><h3>Contact</h3><p><a href="tel:+919342328664">{{ $site['brand']['phone'] ?? '' }}</a></p><p>{{ $site['brand']['address'] ?? '' }}</p><div class="social-row"><a href="{{ $site['social']['instagram'] ?? '#' }}" aria-label="Instagram">Instagram</a><a href="{{ $site['social']['whatsapp'] ?? '#' }}" aria-label="WhatsApp">WhatsApp</a></div></div>
    </div>
    <div class="container footer-qr-band"><div class="footer-qr-copy"><h3>Connect with Ricevibe</h3><p>Scan to follow us or start a WhatsApp chat.</p></div><div class="qr-connect">
      <figure><a href="{{ $site['social']['instagram'] ?? '#' }}" target="_blank" rel="noreferrer"><img src="/static/assets/unnamed%20(1).jpg" alt="Ricevibe Instagram QR code" loading="lazy" /></a><figcaption>Instagram</figcaption></figure>
      <figure><a href="{{ $site['social']['whatsapp'] ?? '#' }}" target="_blank" rel="noreferrer"><img src="/static/assets/unnamed.jpg" alt="Ricevibe WhatsApp QR code" loading="lazy" /></a><figcaption>WhatsApp</figcaption></figure>
    </div></div>
    <div class="container footer-bottom"><p>© {{ date('Y') }} Ricevibe.</p><div class="policy-links"><a href="/shipping-policy">Shipping</a><a href="/returns-policy">Return/Refund</a></div></div>
  </footer>
  <div class="contact-float"><a class="whatsapp-float" href="{{ $site['brand']['whatsapp'] ?? '#' }}" target="_blank" rel="noreferrer" aria-label="WhatsApp enquiry">WA</a><button class="chat-float" type="button" aria-label="Open Ricevibe chat" aria-expanded="false">?</button><div class="chat-panel" hidden><strong>Ricevibe support</strong><p>How can we help?</p><a href="/contact">Product enquiry</a></div></div>
  <script src="/static/js/app.js"></script>
</body>
</html>