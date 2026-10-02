@extends('layouts.app')

@section('content')
<section class="admin-login-wrap">
  <a class="admin-login-brand" href="/" aria-label="Ricevibe home"><img src="/static/assets/ricevibe/logo-cropped.png" alt="Ricevibe" /></a>
  <div class="admin-login-card">
    <p class="admin-eyebrow">Ricevibe workspace</p>
    <h1>Welcome back</h1>
    <p class="admin-login-intro">Sign in to manage your products, banners and customer enquiries.</p>
    <form method="post" action="{{ route('admin.login.submit') }}">
      @csrf
      <label class="admin-field"><span>Email address</span><input name="email" type="email" value="{{ old('email') }}" autocomplete="username" placeholder="name@company.com" required autofocus /></label>
      <label class="admin-field"><span>Password</span><input name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required /></label>
      @error('email')<p class="admin-field-error" role="alert">{{ $message }}</p>@enderror
      <button class="admin-button primary admin-login-submit" type="submit">Sign in <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
    </form>
    <a class="admin-back-link" href="/">← Back to Ricevibe storefront</a>
  </div>
  <p class="admin-login-footnote">Secure access for authorized Ricevibe administrators.</p>
</section>
@endsection
