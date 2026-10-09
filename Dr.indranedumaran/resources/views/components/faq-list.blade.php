@props(['faqs'])
<div class="divide-y divide-ice-200 rounded-3xl bg-card ring-1 ring-ice-200" x-data="{ open: null }">
    @foreach($faqs as $faq)
        <div>
            <h3 class="!font-sans">
                <button type="button" class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left text-base font-semibold text-heading"
                        @click="open = open === {{ $faq->id }} ? null : {{ $faq->id }}" :aria-expanded="open === {{ $faq->id }}" aria-controls="faq-{{ $faq->id }}">
                    <span>{{ $faq->question }}</span>
                    <x-icon name="plus" class="h-5 w-5 shrink-0 text-lilac transition" ::class="open === {{ $faq->id }} && 'rotate-45'" />
                </button>
            </h3>
            <div id="faq-{{ $faq->id }}" x-show="open === {{ $faq->id }}" x-cloak x-transition.opacity.duration.200ms class="px-6 pb-6 text-sm leading-relaxed text-mute">
                {!! nl2br(e($faq->answer)) !!}
            </div>
        </div>
    @endforeach
</div>
