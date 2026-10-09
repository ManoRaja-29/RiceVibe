<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="media-upload-url" content="{{ route('admin.media.store') }}">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Dashboard') · Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600,700|inter:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="admin-theme overflow-x-hidden">
@php
$nav = [
    'Overview' => [['Dashboard', 'admin.dashboard', 'admin.dashboard']],
    'Content' => [
        ['Blog posts', 'admin.posts.index', 'admin.posts.*'],
        ['Categories', 'admin.categories.index', 'admin.categories.*'],
        ['Conferences & events', 'admin.conferences.index', 'admin.conferences.*'],
        ['Services', 'admin.services.index', 'admin.services.*'],
        ['Pregnancy journey', 'admin.pregnancy.index', 'admin.pregnancy.*'],
        ['FAQs', 'admin.faqs.index', 'admin.faqs.*'],
        ['Gallery', 'admin.gallery.index', 'admin.gallery.*'],
        ['Profile & timeline', 'admin.profile-items.index', 'admin.profile-items.*'],
    ],
    'Site' => [
        ['Patient stories', 'admin.stories.index', 'admin.stories.*'],
        ['Consult Q&A', 'admin.questions.index', 'admin.questions.*'],
        ['Enquiries', 'admin.enquiries.index', 'admin.enquiries.*'],
        ['Media library', 'admin.media.index', 'admin.media.*'],
    ],
];
@endphp
<div x-data="{ side: false }" class="min-h-screen lg:flex">
    <div x-show="side" x-cloak @click="side = false" class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"></div>
    <aside :class="side ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 z-40 w-64 shrink-0 overflow-y-auto bg-card p-5 ring-1 ring-slate-200 transition lg:static lg:block">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl bg-gradient-to-r from-brand-600 to-copper p-3 text-white shadow-lg">
            <img src="{{ asset('images/logo-mark.webp') }}" alt="" class="h-11 w-11 rounded-xl bg-[#fff] object-contain p-0.5 ring-2 ring-white/60">
            <span class="font-display text-lg font-semibold leading-tight text-white">Dr. Indhra<br><span class="text-xs font-sans font-medium uppercase tracking-widest text-white/80">Admin</span></span>
        </a>
        <nav class="mt-8 space-y-6 text-sm" aria-label="Admin">
            @foreach($nav as $group => $items)
                <div>
                    <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-widest text-brand-600">{{ $group }}</p>
                    @foreach($items as [$label, $route, $pattern])
                        <a href="{{ route($route) }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs($pattern) ? 'bg-brand-600 font-semibold text-white shadow' : 'text-slate-700 hover:bg-ice-100' }}">{{ $label }}</a>
                    @endforeach
                </div>
            @endforeach
            <div>
                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-widest text-brand-600">Settings</p>
                @foreach(config('site_settings') as $key => $g)
                    <a href="{{ route('admin.settings', $key) }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.settings') && request()->route('group') === $key || (request()->routeIs('admin.settings') && !request()->route('group') && $key === 'general') ? 'bg-brand-600 font-semibold text-white shadow' : 'text-slate-700 hover:bg-ice-100' }}">{{ $g['label'] }}</a>
                @endforeach
                <a href="{{ route('admin.account') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.account') ? 'bg-brand-600 font-semibold text-white shadow' : 'text-slate-700 hover:bg-ice-100' }}">My account</a>
            </div>
        </nav>
    </aside>

    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-card/90 px-4 backdrop-blur sm:px-8">
            <button type="button" class="rounded-lg p-2 text-slate-600 lg:hidden" @click="side = true" aria-label="Open menu"><x-icon name="menu" class="h-6 w-6" /></button>
            <h1 class="font-sans text-base font-semibold text-slate-800">@yield('title', 'Dashboard')</h1>
            <div class="ml-auto flex items-center gap-3 text-sm">
                <a href="{{ route('home') }}" target="_blank" class="text-lilac hover:underline">View site ↗</a>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="a-btn a-btn-ghost">Log out</button></form>
            </div>
        </header>

        <main class="p-4 sm:p-8">
            @if(session('status'))<div role="status" class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('status') }}</div>@endif
            @if($errors->any())
                <div role="alert" class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                    <p class="font-medium">Please fix the following:</p>
                    <ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
