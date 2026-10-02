@extends('layouts.app')

@section('content')
<div class="admin-layout">
  <aside class="admin-sidebar">
    <a class="admin-brand" href="/admin/dashboard">
      <img src="/static/assets/ricevibe/logo-cropped.png" alt="Ricevibe" />
      <span>Store admin<small>Ricevibe workspace</small></span>
    </a>
    <nav class="admin-navigation" aria-label="Admin navigation">
      <a class="active" href="#overview"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="5" rx="1.5"/><rect x="13" y="10" width="8" height="11" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/></svg><span>Overview</span></a>
      <a href="#products"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="M4 7v10l8 4 8-4V7M12 11v10"/></svg><span>Products</span><span class="admin-nav-count">{{ $products->count() }}</span></a>
      <a href="#banners"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/></svg><span>Banners</span></a>
      <a href="#content"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h14M5 8h14M5 12h9M5 16h14M5 20h10"/></svg><span>Site content</span></a>
      <a href="#enquiries"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v12H7l-3 3V5Z"/><path d="M8 9h8M8 13h5"/></svg><span>Enquiries</span><span class="admin-nav-count">{{ $enquiries->count() }}</span></a>
    </nav>
    <div class="admin-sidebar-footer">
      <a class="admin-store-link" href="/" target="_blank" rel="noreferrer"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9"/><path d="M18 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h5"/></svg>View storefront</a>
      <div class="admin-account"><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span class="admin-account-name">{{ auth()->user()->name }}<small>{{ auth()->user()->email }}</small></span></div>
      <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="admin-signout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg>Sign out</button></form>
    </div>
  </aside>

  <main class="admin-main">
    <header class="admin-topbar">
      <div><p class="admin-breadcrumb">Workspace <span>/</span> Overview</p><h1>Store management</h1></div>
      <div class="admin-topbar-status"><span class="admin-status-dot"></span> Store is online <span class="admin-topbar-divider"></span>{{ now()->format('D, M j') }}</div>
    </header>

    @if(session('status'))<div class="admin-alert success" role="status"><span>{{ session('status') }}</span><button type="button" aria-label="Dismiss notification" data-dismiss-alert>×</button></div>@endif
    @if($errors->any())<div class="admin-alert error" role="alert"><span>Please check the submitted fields and try again.</span><button type="button" aria-label="Dismiss notification" data-dismiss-alert>×</button></div>@endif

    <section class="admin-stat-grid" id="overview" aria-label="Store overview">
      <article class="admin-stat-card"><span class="admin-stat-icon product"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="M4 7v10l8 4 8-4V7M12 11v10"/></svg></span><span class="admin-stat-label">Products</span><strong>{{ $products->count() }}</strong><small>In your catalogue</small></article>
      <article class="admin-stat-card"><span class="admin-stat-icon banner"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/></svg></span><span class="admin-stat-label">Active banners</span><strong>{{ $banners->where('is_active', true)->count() }}</strong><small>{{ $banners->count() }} total banners</small></article>
      <article class="admin-stat-card"><span class="admin-stat-icon category"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span><span class="admin-stat-label">Categories</span><strong>{{ $categories->count() }}</strong><small>Product groups</small></article>
      <article class="admin-stat-card"><span class="admin-stat-icon enquiry"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v12H7l-3 3V5Z"/><path d="M8 9h8M8 13h5"/></svg></span><span class="admin-stat-label">Recent enquiries</span><strong>{{ $enquiries->count() }}</strong><small>Latest customer requests</small></article>
    </section>

    <section class="admin-panel" id="products">
      <div class="admin-panel-heading"><div><p class="admin-eyebrow">Catalogue</p><h2>Products <span class="admin-heading-count">{{ $products->count() }}</span></h2><p class="admin-section-note">Manage products, prices and availability.</p></div><button class="admin-button primary" type="button" data-dialog-open="product-create-dialog"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Add product</button></div>
      <div class="admin-table-tools"><label class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input id="admin-product-search" type="search" placeholder="Search products" /></label><select id="admin-product-category" aria-label="Filter products by category"><option value="all">All categories</option>@foreach($categories as $category)<option value="{{ $category->name }}">{{ $category->name }}</option>@endforeach</select></div>
      <div class="admin-table-wrap"><table class="admin-data-table"><thead><tr><th>Product</th><th>Category</th><th>Pack / size</th><th>Price</th><th>Status</th><th><span class="sr-only">Actions</span></th></tr></thead><tbody>
        @forelse($products as $product)
        <tr data-product-row data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category?->name }}">
          <td><div class="admin-product-cell"><img src="{{ str_starts_with($product->image_path ?? '', '/') ? $product->image_path : \Illuminate\Support\Facades\Storage::url($product->image_path ?? '') }}" alt="" loading="lazy" /><span><strong>{{ $product->name }}</strong><small>{{ $product->slug }}</small></span></div></td>
          <td>{{ $product->category?->name ?? 'Uncategorized' }}</td><td>{{ $product->pack ?: '—' }}</td><td>{{ $product->price !== null ? '₹'.number_format($product->price) : 'Enquire' }}</td>
          <td><span class="admin-status-pill {{ $product->is_active ? 'active' : 'inactive' }}"><span></span>{{ $product->is_active ? 'Active' : 'Hidden' }}</span></td>
          <td><div class="admin-row-actions"><button class="admin-icon-button" type="button" data-dialog-open="product-edit-{{ $product->id }}" aria-label="Edit {{ $product->name }}" title="Edit product"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 5 5 5M4 20l4.5-1 10.8-10.8a2.1 2.1 0 0 0-3-3L5.5 16 4 20Z"/></svg></button><form method="post" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">@csrf @method('DELETE')<button class="admin-icon-button danger" type="submit" aria-label="Delete {{ $product->name }}" title="Delete product"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg></button></form></div></td>
        </tr>
        @empty
        <tr><td colspan="6" class="admin-empty">No products found.</td></tr>
        @endforelse
      </tbody></table></div>
    </section>

    <section class="admin-panel" id="banners">
      <div class="admin-panel-heading"><div><p class="admin-eyebrow">Homepage</p><h2>Banners <span class="admin-heading-count">{{ $banners->count() }}</span></h2><p class="admin-section-note">Manage the images displayed in the homepage carousel.</p></div><button class="admin-button primary" type="button" data-dialog-open="banner-create-dialog"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Add banner</button></div>
      <div class="admin-banner-grid">
        @forelse($banners as $banner)
        <article class="admin-banner-card"><div class="admin-banner-preview"><img src="{{ str_starts_with($banner->image_path, '/') ? $banner->image_path : \Illuminate\Support\Facades\Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" loading="lazy" /><span class="admin-status-pill {{ $banner->is_active ? 'active' : 'inactive' }}"><span></span>{{ $banner->is_active ? 'Active' : 'Hidden' }}</span></div><div class="admin-banner-copy"><div><h3>{{ $banner->title }}</h3><p>{{ $banner->subtitle }}</p></div><div class="admin-row-actions"><button class="admin-icon-button" type="button" data-dialog-open="banner-edit-{{ $banner->id }}" aria-label="Edit {{ $banner->title }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 5 5 5M4 20l4.5-1 10.8-10.8a2.1 2.1 0 0 0-3-3L5.5 16 4 20Z"/></svg></button><form method="post" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete this banner?')">@csrf @method('DELETE')<button class="admin-icon-button danger" type="submit" aria-label="Delete {{ $banner->title }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg></button></form></div></div></article>
        @empty
        <p class="admin-empty">No banners found.</p>
        @endforelse
      </div>
    </section>

    <section class="admin-panel" id="content">
      <div class="admin-panel-heading"><div><p class="admin-eyebrow">Pages & settings</p><h2>Site content</h2><p class="admin-section-note">Edit the structured content used across the storefront.</p></div></div>
      <div class="admin-content-list">
        @foreach($contents as $content)
        <details class="admin-content-item"><summary><span>{{ str_replace('_', ' ', ucfirst($content->section)) }}</span><span class="admin-content-meta">JSON <span aria-hidden="true">⌄</span></span></summary><form method="post" action="{{ route('admin.content.update', $content->section) }}">@csrf @method('PUT')<label class="sr-only" for="content-{{ $content->section }}">{{ $content->section }} JSON</label><textarea id="content-{{ $content->section }}" name="payload" rows="12" spellcheck="false">{{ json_encode($content->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>@error('payload')<p class="admin-field-error">{{ $message }}</p>@enderror<button class="admin-button secondary" type="submit">Save {{ str_replace('_', ' ', $content->section) }}</button></form></details>
        @endforeach
      </div>
    </section>

    <section class="admin-panel" id="enquiries">
      <div class="admin-panel-heading"><div><p class="admin-eyebrow">Customer requests</p><h2>Recent enquiries <span class="admin-heading-count">{{ $enquiries->count() }}</span></h2><p class="admin-section-note">Latest messages submitted through the contact form.</p></div></div>
      <div class="admin-table-wrap"><table class="admin-data-table"><thead><tr><th>Customer</th><th>Contact</th><th>Type</th><th>Product</th><th>Message</th><th>Received</th></tr></thead><tbody>
        @forelse($enquiries as $enquiry)
        <tr><td><strong>{{ $enquiry->name }}</strong><small class="admin-cell-subtext">{{ $enquiry->company }}</small></td><td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a><small class="admin-cell-subtext">{{ $enquiry->phone }}</small></td><td>{{ $enquiry->enquiry_type }}</td><td>{{ $enquiry->product ?: '—' }}</td><td class="admin-message-cell">{{ $enquiry->message }}</td><td>{{ $enquiry->created_at->format('M j, Y') }}</td></tr>
        @empty
        <tr><td colspan="6" class="admin-empty">No enquiries received yet.</td></tr>
        @endforelse
      </tbody></table></div>
    </section>
  </main>
</div>

<dialog class="admin-dialog" id="product-create-dialog" data-admin-dialog><form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">@csrf<div class="admin-dialog-head"><div><p class="admin-eyebrow">Catalogue</p><h2>Add product</h2></div><button class="admin-icon-button" type="button" data-dialog-close aria-label="Close dialog">×</button></div><div class="admin-dialog-body"><div class="admin-form-grid"><label class="admin-field full">Product name<input name="name" required maxlength="160" /></label><label class="admin-field">Slug<input name="slug" maxlength="180" /></label><label class="admin-field">Category<select name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></label><label class="admin-field">Pack / size<input name="pack" maxlength="180" /></label><label class="admin-field">Price in INR<input name="price" type="number" min="0" /></label><label class="admin-field full">Description<textarea name="description" rows="3" maxlength="5000"></textarea></label><label class="admin-field">Usage<textarea name="usage" rows="3" maxlength="5000"></textarea></label><label class="admin-field">Stock label<input name="stock" maxlength="100" /></label><label class="admin-field full">Product image<input name="image_file" type="file" accept="image/*" /></label><label class="admin-field"><span>Visibility</span><span class="admin-check"><input type="hidden" name="is_active" value="0" /><input type="checkbox" name="is_active" value="1" checked />Show in store</span></label></div></div><div class="admin-dialog-actions"><button class="admin-button secondary" type="button" data-dialog-close>Cancel</button><button class="admin-button primary" type="submit">Add product</button></div></form></dialog>

@foreach($products as $product)
<dialog class="admin-dialog" id="product-edit-{{ $product->id }}" data-admin-dialog><form method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">@csrf @method('PUT')<div class="admin-dialog-head"><div><p class="admin-eyebrow">Catalogue</p><h2>Edit product</h2></div><button class="admin-icon-button" type="button" data-dialog-close aria-label="Close dialog">×</button></div><div class="admin-dialog-body"><div class="admin-form-grid"><label class="admin-field full">Product name<input name="name" value="{{ $product->name }}" required maxlength="160" /></label><label class="admin-field">Slug<input name="slug" value="{{ $product->slug }}" maxlength="180" /></label><label class="admin-field">Category<select name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected($category->id === $product->category_id)>{{ $category->name }}</option>@endforeach</select></label><label class="admin-field">Pack / size<input name="pack" value="{{ $product->pack }}" maxlength="180" /></label><label class="admin-field">Price in INR<input name="price" type="number" min="0" value="{{ $product->price }}" /></label><label class="admin-field full">Description<textarea name="description" rows="3" maxlength="5000">{{ $product->description }}</textarea></label><label class="admin-field">Usage<textarea name="usage" rows="3" maxlength="5000">{{ $product->usage }}</textarea></label><label class="admin-field">Stock label<input name="stock" value="{{ $product->stock }}" maxlength="100" /></label><label class="admin-field full">Replace image<input name="image_file" type="file" accept="image/*" /></label><label class="admin-field"><span>Visibility</span><span class="admin-check"><input type="hidden" name="is_active" value="0" /><input type="checkbox" name="is_active" value="1" @checked($product->is_active) />Show in store</span></label></div></div><div class="admin-dialog-actions"><button class="admin-button secondary" type="button" data-dialog-close>Cancel</button><button class="admin-button primary" type="submit">Save changes</button></div></form></dialog>
@endforeach

<dialog class="admin-dialog" id="banner-create-dialog" data-admin-dialog><form method="post" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">@csrf<div class="admin-dialog-head"><div><p class="admin-eyebrow">Homepage</p><h2>Add banner</h2></div><button class="admin-icon-button" type="button" data-dialog-close aria-label="Close dialog">×</button></div><div class="admin-dialog-body"><div class="admin-form-grid"><label class="admin-field full">Title<input name="title" required maxlength="160" /></label><label class="admin-field full">Subtitle<input name="subtitle" maxlength="255" /></label><label class="admin-field full">Banner image<input name="image_file" type="file" accept="image/*" required /></label><label class="admin-field">Button text<input name="button_text" maxlength="80" /></label><label class="admin-field">Button link<input name="button_link" value="/shop" maxlength="255" /></label><label class="admin-field"><span>Visibility</span><span class="admin-check"><input type="hidden" name="is_active" value="0" /><input type="checkbox" name="is_active" value="1" checked />Show on homepage</span></label></div></div><div class="admin-dialog-actions"><button class="admin-button secondary" type="button" data-dialog-close>Cancel</button><button class="admin-button primary" type="submit">Add banner</button></div></form></dialog>

@foreach($banners as $banner)
<dialog class="admin-dialog" id="banner-edit-{{ $banner->id }}" data-admin-dialog><form method="post" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">@csrf @method('PUT')<div class="admin-dialog-head"><div><p class="admin-eyebrow">Homepage</p><h2>Edit banner</h2></div><button class="admin-icon-button" type="button" data-dialog-close aria-label="Close dialog">×</button></div><div class="admin-dialog-body"><div class="admin-form-grid"><label class="admin-field full">Title<input name="title" value="{{ $banner->title }}" required maxlength="160" /></label><label class="admin-field full">Subtitle<input name="subtitle" value="{{ $banner->subtitle }}" maxlength="255" /></label><label class="admin-field full">Replace image<input name="image_file" type="file" accept="image/*" /></label><label class="admin-field">Button text<input name="button_text" value="{{ $banner->button_text }}" maxlength="80" /></label><label class="admin-field">Button link<input name="button_link" value="{{ $banner->button_link }}" maxlength="255" /></label><label class="admin-field"><span>Visibility</span><span class="admin-check"><input type="hidden" name="is_active" value="0" /><input type="checkbox" name="is_active" value="1" @checked($banner->is_active) />Show on homepage</span></label></div></div><div class="admin-dialog-actions"><button class="admin-button secondary" type="button" data-dialog-close>Cancel</button><button class="admin-button primary" type="submit">Save changes</button></div></form></dialog>
@endforeach
@endsection
