@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow">Admin</span><h1>Sign in</h1></div></section>
<section class="section"><div class="container"><form class="contact-form" method="post" action="{{ route('admin.login.submit') }}">@csrf<label><span>Email</span><input name="email" type="email" value="{{ old('email') }}" autocomplete="username" required /></label><label><span>Password</span><input name="password" type="password" autocomplete="current-password" required /></label>@error('email')<p class="form-status">{{ $message }}</p>@enderror<button class="btn primary" type="submit">Sign in</button></form></div></section>
@endsection