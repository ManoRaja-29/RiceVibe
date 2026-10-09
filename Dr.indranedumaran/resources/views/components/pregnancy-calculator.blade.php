<div id="pregnancy-calculator" class="scroll-mt-28 rounded-[2.5rem] bg-card p-8 ring-1 ring-ice-200 sm:p-12"
     x-data="{
        lmp: '', cycle: 28, result: null,
        calc() {
            if (!this.lmp) { this.result = null; return; }
            const day = 86400000;
            const start = new Date(this.lmp + 'T00:00:00');
            const cycle = Math.min(45, Math.max(21, parseInt(this.cycle) || 28));
            const due = new Date(start.getTime() + (280 + cycle - 28) * day);
            const today = new Date(); today.setHours(0, 0, 0, 0);
            const days = Math.floor((today - start) / day);
            const fmt = d => d.toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' });
            const r = { due: fmt(due), valid: days >= 0 && days <= 300, weeks: Math.floor(days / 7), rem: days % 7 };
            r.tri = r.weeks < 13 ? 'first trimester' : (r.weeks < 29 ? 'second trimester' : 'third trimester');
            r.t1 = fmt(new Date(start.getTime() + 84 * day));
            r.t2 = fmt(new Date(start.getTime() + 196 * day));
            this.result = r;
        }
     }">
    <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <span class="eyebrow">Pregnancy calculator</span>
            <h2 class="h-section">Estimate your due date</h2>
            <p class="lead mt-4">Enter the first day of your last menstrual period to see an estimated due date and how many weeks pregnant you may be.</p>
            <p class="mt-4 text-xs text-mute">This is a general estimate only. Your doctor may adjust the date after a dating scan.</p>
        </div>
        <form @submit.prevent="calc()" class="space-y-4">
            <div>
                <label class="label" for="lmp-{{ $attributes->get('id', 'calc') }}">First day of last period</label>
                <input class="input" type="date" id="lmp-{{ $attributes->get('id', 'calc') }}" x-model="lmp" :max="new Date().toISOString().slice(0, 10)" required>
            </div>
            <div>
                <label class="label" for="cycle-{{ $attributes->get('id', 'calc') }}">Average cycle length (days)</label>
                <input class="input" type="number" min="21" max="45" id="cycle-{{ $attributes->get('id', 'calc') }}" x-model="cycle">
            </div>
            <button type="submit" class="btn btn-primary">Calculate</button>

            <div x-show="result" x-cloak class="rounded-2xl bg-ice-50 p-5 ring-1 ring-ice-200" aria-live="polite">
                <p class="text-xs font-semibold uppercase tracking-widest text-copper">Estimated due date</p>
                <p class="mt-1 font-display text-3xl text-lilac" x-text="result && result.due"></p>
                <template x-if="result && result.valid">
                    <p class="mt-3 text-sm text-mute">You may be about <strong x-text="result.weeks + ' weeks ' + result.rem + ' days'"></strong> pregnant (<span x-text="result.tri"></span>).</p>
                </template>
                <template x-if="result && !result.valid">
                    <p class="mt-3 text-sm text-mute">Please check the date entered — it should be within the last 10 months.</p>
                </template>
                <p class="mt-3 text-xs text-mute" x-show="result">First trimester ends around <span x-text="result && result.t1"></span>; second trimester around <span x-text="result && result.t2"></span>.</p>
            </div>
        </form>
    </div>
</div>
