<footer class="mt-0 bg-ice-50 print:hidden">
    <div class="container-x grid gap-12 py-16 md:grid-cols-12">
        <div class="md:col-span-5">
            <img src="{{ asset('images/logo-full.webp') }}" alt="{{ setting('site_name') }} – Gynecologist" width="1400" height="389" loading="lazy" class="h-auto w-full max-w-sm rounded-2xl bg-[#fff] p-3 shadow-xl ring-1 ring-white/40">
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-mute">{{ setting('tagline') }}</p>
            <div class="mt-5 flex gap-2">
                @foreach(['facebook' => 'facebook', 'instagram' => 'instagram', 'youtube' => 'youtube', 'linkedin' => 'linkedin', 'x_twitter' => 'x'] as $key => $icon)
                    @if(setting($key))
                        <a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst(str_replace('_twitter', '', $key)) }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-card text-lilac ring-1 ring-sky-pastel transition hover:bg-brand-600 hover:text-white"><x-icon :name="$icon" /></a>
                    @endif
                @endforeach
            </div>
        </div>

        <nav class="md:col-span-3" aria-label="Footer">
            <h2 class="font-display text-xl">Explore</h2>
            <ul class="mt-4 space-y-2 text-sm text-mute">
                <li><a class="hover:text-lilac" href="{{ route('about') }}">About the doctor</a></li>
                <li><a class="hover:text-lilac" href="{{ route('fertility') }}">Fertility</a></li>
                <li><a class="hover:text-lilac" href="{{ route('gynaecology') }}">Gynaecology</a></li>
                <li><a class="hover:text-lilac" href="{{ route('maternity') }}">Pregnancy &amp; maternity care</a></li>
                <li><a class="hover:text-lilac" href="{{ route('blogs.index') }}">Blogs</a></li>
                <li><a class="hover:text-lilac" href="{{ route('activities.index') }}">Conferences &amp; activities</a></li>
                <li><a class="hover:text-lilac" href="{{ route('stories') }}">Patient stories</a></li>
                <li><a class="hover:text-lilac" href="{{ route('qa.index') }}">Consult Q&amp;A</a></li>
                <li><a class="hover:text-lilac" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </nav>

        <div class="md:col-span-4">
            <h2 class="font-display text-xl">Get in touch</h2>
            <ul class="mt-4 space-y-3 text-sm text-mute">
                <li class="flex gap-3"><x-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-lilac" /><a href="tel:{{ preg_replace('/[^\d+]/', '', setting('phone')) }}" class="hover:text-lilac">{{ setting('phone') }}</a></li>
                @if(setting('email'))<li class="flex gap-3"><x-icon name="mail" class="mt-0.5 h-4 w-4 shrink-0 text-lilac" /><a href="mailto:{{ setting('email') }}" class="break-all hover:text-lilac">{{ setting('email') }}</a></li>@endif
                <li class="flex gap-3"><x-icon name="pin" class="mt-0.5 h-4 w-4 shrink-0 text-lilac" /><span class="whitespace-pre-line">{{ setting('address') }}</span></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-ice-200">
        <div class="container-x flex flex-col gap-3 py-6 text-xs leading-relaxed text-mute sm:flex-row sm:items-start sm:justify-between">
            <p class="max-w-3xl">{{ setting('footer_disclaimer') }}</p>
            <p class="shrink-0">© {{ date('Y') }} {{ setting('site_name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
