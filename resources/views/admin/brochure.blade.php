@extends('layouts.app')

@section('content')
<div class="admin-layout">
  <aside class="admin-sidebar">
    <a class="admin-brand" href="{{ route('admin.dashboard') }}">
      <img src="/static/assets/ricevibe/logo-cropped.png" alt="Ricevibe" />
      <span>Store admin<small>Ricevibe workspace</small></span>
    </a>
    <nav class="admin-navigation" aria-label="Admin navigation">
      <a href="{{ route('admin.dashboard') }}"><span>← Dashboard</span></a>
      <a class="active" href="{{ route('admin.brochure') }}"><span>Brochure</span></a>
    </nav>
    <div class="admin-sidebar-footer">
      <a class="admin-store-link" href="/brochure" target="_blank" rel="noreferrer">View brochure page</a>
      <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="admin-signout" type="submit">Sign out</button></form>
    </div>
  </aside>

  <main class="admin-main">
    <header class="admin-topbar">
      <div><p class="admin-breadcrumb">Workspace <span>/</span> Brochure</p><h1>Brochure management</h1></div>
    </header>

    @if(session('status'))<div class="admin-alert success" role="status"><span>{{ session('status') }}</span></div>@endif
    @if($errors->any())<div class="admin-alert error" role="alert"><span>{{ $errors->first() }}</span></div>@endif

    <section class="admin-panel">
      <div class="admin-panel-heading">
        <div>
          <p class="admin-eyebrow">Official document</p>
          <h2>Current brochure</h2>
          <p class="admin-section-note">{{ $hasCustomBrochure ? 'A custom brochure is currently active.' : 'The original website brochure is currently active.' }}</p>
        </div>
        <a class="admin-button secondary" href="{{ route('brochure.view') }}" target="_blank" rel="noreferrer">View current PDF</a>
      </div>

      <div style="padding: 0 24px 24px;">
        <iframe src="{{ route('brochure.view') }}" title="Current Ricevibe brochure" style="width:100%;min-height:520px;border:1px solid #ddd;border-radius:12px;background:#fff;"></iframe>
      </div>
    </section>

    <section class="admin-panel">
      <div class="admin-panel-heading">
        <div>
          <p class="admin-eyebrow">Replace brochure</p>
          <h2>Upload a new PDF</h2>
          <p class="admin-section-note">The new PDF will immediately replace the brochure shown on the website. Maximum file size: 20 MB.</p>
        </div>
      </div>

      <form method="post" action="{{ route('admin.brochure.update') }}" enctype="multipart/form-data" style="padding:0 24px 24px;display:grid;gap:16px;">
        @csrf
        @method('PUT')
        <label>
          <span style="display:block;font-weight:700;margin-bottom:8px;">Choose brochure PDF</span>
          <input name="brochure_file" type="file" accept="application/pdf,.pdf" required />
        </label>
        <div>
          <button class="admin-button primary" type="submit" onclick="return confirm('Replace the current website brochure with this PDF?')">Replace brochure</button>
        </div>
      </form>
    </section>
  </main>
</div>
@endsection
