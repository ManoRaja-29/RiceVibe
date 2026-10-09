@extends('layouts.app')

@section('content')
<x-page-header title="Contact us" eyebrow="Contact" :intro="setting('contact_text')" :crumbs="['Contact' => null]" />

<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-5">
            <div class="card">
                <h2 class="text-3xl">{{ setting('clinic_name') }}</h2>
                <ul class="mt-5 space-y-4 text-sm">
                    <li class="flex gap-3"><x-icon name="pin" class="mt-0.5 h-5 w-5 shrink-0 text-lilac" /><span class="whitespace-pre-line">{{ setting('address') }}</span></li>
                    <li class="flex gap-3"><x-icon name="phone" class="mt-0.5 h-5 w-5 shrink-0 text-lilac" /><a href="tel:{{ preg_replace('/[^\d+]/', '', setting('phone')) }}" class="hover:text-lilac">{{ setting('phone') }}</a></li>
                    @if(setting('email'))<li class="flex gap-3"><x-icon name="mail" class="mt-0.5 h-5 w-5 shrink-0 text-lilac" /><a href="mailto:{{ setting('email') }}" class="break-all hover:text-lilac">{{ setting('email') }}</a></li>@endif
                    @if(setting('hours'))<li class="flex gap-3"><x-icon name="clock" class="mt-0.5 h-5 w-5 shrink-0 text-lilac" /><span class="whitespace-pre-line">{{ setting('hours') }}</span></li>@endif
                </ul>
                <div class="mt-6 flex flex-wrap gap-3">
                    @if(setting('whatsapp'))<a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" class="btn btn-primary"><x-icon name="whatsapp" class="h-4 w-4" /> WhatsApp</a>@endif
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', setting('phone')) }}" class="btn btn-soft"><x-icon name="phone" class="h-4 w-4" /> Call</a>
                    @if(setting('map_link'))<a href="{{ setting('map_link') }}" target="_blank" rel="noopener" class="btn btn-soft">Directions</a>@endif
                </div>
                <div class="mt-6 flex gap-2">
                    @foreach(['facebook' => 'facebook', 'instagram' => 'instagram', 'youtube' => 'youtube', 'linkedin' => 'linkedin', 'x_twitter' => 'x'] as $key => $icon)
                        @if(setting($key))<a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ $key }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-ice-100 text-lilac hover:bg-brand-600 hover:text-white"><x-icon :name="$icon" /></a>@endif
                    @endforeach
                </div>
            </div>
            <p class="rounded-2xl bg-peach-50 p-4 text-sm text-mute ring-1 ring-peach-100">For urgent symptoms such as heavy bleeding, severe pain or reduced baby movements, please contact emergency services or go to your nearest hospital — do not wait for a reply to this form.</p>
        </div>

        <div class="lg:col-span-7" id="enquiry">
            @if(session('sent'))
                <div class="card border-0 bg-ice-50 text-center" role="status">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-card text-lilac"><x-icon name="check" class="h-7 w-7" /></span>
                    <h2 class="mt-4 text-3xl">Thank you — your message has been received</h2>
                    <p class="mt-2 text-sm text-mute">Our team will get back to you as soon as possible.</p>
                </div>
            @else
            <form method="POST" action="{{ route('contact.store') }}" class="card space-y-5" novalidate>
                @csrf
                <input type="hidden" name="_ft" value="{{ $formTime }}">
                <div class="hidden" aria-hidden="true"><label>Leave empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label class="label" for="name">Full name *</label><input class="input" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">@error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="label" for="email">Email *</label><input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="label" for="phone">Phone / WhatsApp</label><input class="input" id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel">@error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label class="label" for="subject">Topic</label>
                        <select class="input" id="subject" name="subject">
                            <option value="">Select a topic (optional)</option>
                            @foreach(['Fertility', 'Gynaecology', 'Pregnancy & maternity care', 'Menopause', 'Other'] as $o)<option @selected(old('subject') === $o)>{{ $o }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <div><label class="label" for="message">Message *</label><textarea class="input" id="message" name="message" rows="5" required minlength="10">{{ old('message') }}</textarea>@error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div>
                    <label class="flex items-start gap-3 text-sm text-mute"><input type="checkbox" name="consent" value="1" class="mt-1 rounded text-lilac focus:ring-brand-500" @checked(old('consent'))> <span>I agree to be contacted about my enquiry. Please don’t include sensitive medical details in this form.</span></label>
                    @error('consent')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button class="btn btn-primary w-full sm:w-auto" type="submit">Send enquiry</button>
            </form>
            @endif
        </div>
    </div>

    <div class="container-x mt-16">
        <h2 class="text-3xl">Find the clinic</h2>
        <div class="mt-6 overflow-hidden rounded-3xl bg-ice-100 ring-1 ring-ice-200">
            @if(setting('map_embed_url'))
                <iframe src="{{ setting('map_embed_url') }}" title="Clinic location on Google Maps" class="h-96 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @else
                <div class="flex h-72 flex-col items-center justify-center gap-2 text-center text-sm text-mute"><x-icon name="pin" class="h-8 w-8 text-brand-400" /> {{ str_replace("\n", ', ', setting('address')) }}</div>
            @endif
        </div>
    </div>
</section>
@endsection
