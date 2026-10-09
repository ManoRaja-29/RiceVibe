<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Admin login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600,700|inter:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-ice-100 via-card to-lavender-50 p-4">
    <div class="w-full max-w-md rounded-3xl bg-card p-8 shadow-xl ring-1 ring-ice-200">
        <img src="{{ asset('images/logo-full.webp') }}" alt="Dr. Indra Nedumaran" width="1400" height="389" class="mx-auto h-auto w-full max-w-xs rounded-2xl bg-[#fff] p-2 shadow-lg ring-1 ring-ice-200">
        <h1 class="mt-4 text-center text-3xl">Admin sign in</h1>
        <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <div><label class="label" for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"></div>
            <div><label class="label" for="password">Password</label><input class="input" id="password" name="password" type="password" required autocomplete="current-password"></div>
            @error('email')<p class="text-sm text-red-600" role="alert">{{ $message }}</p>@enderror
            <label class="flex items-center gap-2 text-sm text-mute"><input type="checkbox" name="remember" class="rounded text-lilac"> Keep me signed in</label>
            <button class="btn btn-primary w-full">Sign in</button>
        </form>
    </div>
</body>
</html>
