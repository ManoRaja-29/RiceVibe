<div class="rich space-y-10">
    @foreach([[1, 12, 'First trimester', 'Weeks 1–12'], [13, 28, 'Second trimester', 'Weeks 13–28'], [29, 40, 'Third trimester', 'Weeks 29–40']] as [$a, $b, $h, $r])
        <div>
            <h2 class="!mt-0 text-3xl">{{ $h }} <span class="text-base font-sans font-normal text-mute">· {{ $r }}</span></h2>
            <div class="wk-grid mt-4">
                @for($w = $a; $w <= $b; $w++)
                    @php [$title, $baby, $mother] = config("pregnancy_weeks.{$w}"); @endphp
                    <div class="wk" tabindex="0">
                        <b>{{ $w }}</b><small>Week</small>
                        <div class="wk-d"><strong>Week {{ $w }} · {{ $title }}</strong><p><em>Baby:</em> {{ $baby }}</p><p><em>Mother:</em> {{ $mother }}</p></div>
                    </div>
                @endfor
            </div>
        </div>
    @endforeach
    <p class="text-xs text-mute">Weeks are counted from the first day of your last period. General educational information; every pregnancy is different, so follow your doctor’s advice.</p>
</div>