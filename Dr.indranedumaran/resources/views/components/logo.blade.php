@props(['size' => 'md', 'tagline' => true, 'stack' => false])
@php
    $mark = ['sm' => 'h-10 w-10', 'md' => 'h-14 w-14', 'lg' => 'h-20 w-20'][$size] ?? 'h-14 w-14';
    $name = ['sm' => 'text-lg', 'md' => 'text-2xl', 'lg' => 'text-3xl'][$size] ?? 'text-2xl';
@endphp
<span {{ $attributes->class(['inline-flex shrink-0 items-center gap-3 whitespace-nowrap', 'flex-col text-center' => $stack]) }}>
    <span class="relative shrink-0">
        <span class="absolute -inset-1.5 rounded-[1.4rem] bg-gradient-to-br from-brand-400 via-copper to-sky-pastel opacity-60 blur-md" aria-hidden="true"></span>
        <img src="{{ asset('images/logo-mark.webp') }}" alt="" width="80" height="80" class="relative {{ $mark }} rounded-2xl bg-[#fff] object-contain p-0.5 ring-2 ring-white/70">
    </span>
    <span class="leading-tight">
        <span class="block font-display {{ $name }} font-semibold text-heading">{{ setting('doctor_name') }}</span>
        @if($tagline)<span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-[0.25em] text-peach-300">Obstetrician · Gynaecologist</span>@endif
    </span>
</span>
