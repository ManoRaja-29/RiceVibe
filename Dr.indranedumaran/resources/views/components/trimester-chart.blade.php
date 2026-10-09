@php
$weeks = [
    ['w' => 4,  'title' => 'Implantation',        'tri' => 1, 'q' => 'first trimester', 'baby' => 'A tiny ball of cells settles into the lining of the uterus and the placenta begins to form.', 'mother' => 'A missed period is often the first sign. Tender breasts and tiredness are common, and a pregnancy test can now be positive.'],
    ['w' => 9,  'title' => 'Early development',   'tri' => 1, 'q' => 'first trimester', 'baby' => 'The heart is beating and the arms, legs and facial features are forming.', 'mother' => 'Nausea, tiredness and frequent urination are common. Folic acid and early antenatal care matter now.'],
    ['w' => 13, 'title' => 'End of first trimester', 'tri' => 1, 'q' => 'first trimester', 'baby' => 'The main organs have formed and the baby continues to grow quickly.', 'mother' => 'Nausea often eases and energy starts to return. A dating or screening scan is usually offered around this time.'],
    ['w' => 18, 'title' => 'Growing and moving',  'tri' => 2, 'q' => 'second trimester', 'baby' => 'The baby is moving more, although you may not feel it yet.', 'mother' => 'The bump becomes visible. Some mothers feel gentle flutters. The detailed anomaly scan is usually planned between 18 and 22 weeks.'],
    ['w' => 22, 'title' => 'Pregnancy comfort',   'tri' => 2, 'q' => 'second trimester', 'baby' => 'Movements become easier to feel, and you may notice your baby moving more often or more regularly.', 'mother' => 'Backache, stretching and mild swelling can appear. Gentle exercise and good posture can help.'],
    ['w' => 27, 'title' => 'End of second trimester', 'tri' => 2, 'q' => 'second trimester', 'baby' => 'Organs are developed and the baby has regular patterns of movement and rest.', 'mother' => 'Routine blood sugar screening is commonly done around this time. Antenatal classes are a good idea.'],
    ['w' => 31, 'title' => 'Preparing for birth', 'tri' => 3, 'q' => 'third trimester', 'baby' => 'The baby is gaining weight steadily and the organs are working.', 'mother' => 'Breathlessness, heartburn and disturbed sleep are common. Get to know your baby’s normal movements.'],
    ['w' => 36, 'title' => 'Nearly there',        'tri' => 3, 'q' => 'third trimester', 'baby' => 'The baby is getting into position for birth, often head down.', 'mother' => 'Check-ups become more frequent. Finalise your birth plan and pack your hospital bag.'],
    ['w' => 40, 'title' => 'Due date',            'tri' => 3, 'q' => 'third trimester', 'baby' => 'The baby is ready to be born. A baby is considered full term from 37 completed weeks.', 'mother' => 'Know the signs of labour and warning signs, and contact your doctor or hospital if you are unsure.'],
];
$tris = [
    ['n' => '1st', 'weeks' => 'Weeks 1 – 12',  'kg' => '0.4 kg – 2.0 kg',  'bar' => 'bg-brand-400', 'txt' => 'text-lilac'],
    ['n' => '2nd', 'weeks' => 'Weeks 13 – 28', 'kg' => '2.0 kg – 8.0 kg',  'bar' => 'bg-brand-600', 'txt' => 'text-lilac'],
    ['n' => '3rd', 'weeks' => 'Weeks 29 – 40', 'kg' => '8.0 kg – 13.6 kg', 'bar' => 'bg-copper',    'txt' => 'text-copper'],
];
@endphp
<div id="trimester-chart" class="scroll-mt-28 rounded-[2.5rem] bg-card p-6 ring-1 ring-ice-200 sm:p-10" x-data="{ i: null, sel: null, pick(k) { this.sel = this.sel === k ? null : k; this.i = this.sel } }">
    <div class="max-w-2xl">
        <span class="eyebrow">Pregnancy week by week</span>
        <h2 class="h-section">How your pregnancy grows</h2>
        <p class="lead mt-4">Tap a week to see what is happening for you and your baby, then read related articles on our blog. Every pregnancy is different — your doctor will advise you individually.</p>
    </div>

    <div class="mt-10 pb-2">
        <div class="relative mx-auto">
            <ol class="grid grid-cols-9">
                @foreach($weeks as $k => $w)
                    <li class="flex flex-col items-center">
                        <button type="button" @click="pick({{ $k }})" @mouseenter="i = {{ $k }}" @mouseleave="i = sel" :aria-pressed="i === {{ $k }}" aria-label="Week {{ $w['w'] }}: {{ $w['title'] }}" class="group flex flex-col items-center rounded-2xl pt-2 w-full">
                            <span class="block aspect-[130/285] w-full max-w-36 bg-no-repeat transition duration-300" :class="i === {{ $k }} ? 'opacity-100' : 'opacity-50 group-hover:opacity-80'" style="background-image:url('{{ asset('images/trimesters.png') }}'); background-size:300% 100%; background-position:{{ [0, 50, 100][$w['tri'] - 1] }}% 0;" aria-hidden="true"></span>
                        </button>
                    </li>
                @endforeach
            </ol>

            <div class="pointer-events-none absolute inset-x-0 top-2 h-0">
                @foreach($weeks as $k => $w)
                    <div x-show="i === {{ $k }}" x-cloak x-transition.opacity.duration.200ms @mouseenter="i = {{ $k }}" @mouseleave="i = sel" role="region" aria-live="polite" aria-label="Week {{ $w['w'] }}"
                         class="pointer-events-auto absolute w-72 rounded-[1.75rem] border-2 border-brand-400 bg-card p-5 shadow-xl"
                         style="left: clamp(0px, calc({{ round(($k + 0.5) / 9 * 100, 2) }}% - 9rem), calc(100% - 18rem));">
                        <p class="text-xs font-semibold uppercase tracking-widest text-copper">Week {{ $w['w'] }} · {{ ['', 'First', 'Second', 'Third'][$w['tri']] }} trimester</p>
                        <h3 class="mt-1 text-2xl">{{ $w['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-mute"><span class="font-semibold text-lilac">BABY ·</span> {{ $w['baby'] }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-mute"><span class="font-semibold text-lilac">MOTHER ·</span> {{ $w['mother'] }}</p>
                        <a href="{{ route('blogs.index', ['q' => $w['q']]) }}" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-copper hover:text-lilac">Know more — read related blogs <x-icon name="arrow" class="h-4 w-4" /></a>
                    </div>
                @endforeach
            </div>

            <ol class="mt-3 grid grid-cols-9">
                @foreach($weeks as $k => $w)
                    <li class="flex justify-center">
                        <button type="button" @click="pick({{ $k }})" @mouseenter="i = {{ $k }}" @mouseleave="i = sel" class="w-full max-w-14 py-2 text-sm font-semibold text-white transition" :class="i === {{ $k }} ? 'bg-brand-600 ring-2 ring-brand-400 ring-offset-2' : 'bg-copper hover:bg-brand-500'" aria-label="Go to week {{ $w['w'] }}">{{ $w['w'] }}</button>
                    </li>
                @endforeach
            </ol>
            <p class="mt-2 text-right text-xs text-mute">*units in weeks</p>
        </div>
    </div>

    <div class="mt-10 grid gap-8 border-t border-ice-200 pt-10 sm:grid-cols-3">
        @foreach($tris as $t)
            <div class="text-center">
                <h3 class="font-display text-3xl {{ $t['txt'] }}">{{ $t['n'] }} Trimester</h3>
                <p class="mt-1 text-xs uppercase tracking-widest text-mute">{{ $t['weeks'] }}</p>
                <div class="relative mx-auto mt-4 h-0.5 w-4/5 {{ $t['bar'] }}"><span class="absolute -left-1.5 -top-1.5 h-3.5 w-3.5 rounded-full {{ $t['bar'] }}"></span><span class="absolute -right-1.5 -top-1.5 h-3.5 w-3.5 rounded-full {{ $t['bar'] }}"></span></div>
                <p class="mt-4 text-lg text-ink">{{ $t['kg'] }}</p>
                <a href="{{ route('blogs.index', ['q' => strtolower($t['n']).' trimester']) }}" class="mt-2 inline-block text-sm font-semibold text-copper hover:text-lilac">Related articles →</a>
            </div>
        @endforeach
    </div>
    <p class="mt-6 text-xs text-mute">Typical total weight gain across each stage; it varies with your starting weight, so follow your doctor’s advice. General educational information — seek medical help straight away for heavy bleeding, severe pain or reduced baby movements.</p>
</div>
