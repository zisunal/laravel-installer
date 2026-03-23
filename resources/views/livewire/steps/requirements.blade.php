<div class="rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
    @include('installer::partials.navigation', ['steps' => $steps])

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">{{ static::title() }}</h2>
            <p class="mt-2 text-slate-600">{{ static::description() }}</p>
        </div>

        <div class="grid gap-3">
            @foreach ($checks as $check)
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3">
                    <span class="text-sm text-slate-700">{{ $check['label'] }}</span>
                    @if ($check['passed'])
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Passed</span>
                    @else
                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Failed</span>
                    @endif
                </div>
            @endforeach
        </div>

        @if (! $this->allPassed())
            <div class="rounded-2xl bg-rose-50 p-4 text-sm text-rose-700">
                Some requirements are missing. Fix them before continuing.
            </div>
        @endif

        <div class="flex items-center justify-between">
            <button
                type="button"
                wire:click="previousStep"
                class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Back
            </button>

            <button
                type="button"
                wire:click="nextStep"
                class="cursor-pointer rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50"
                @disabled(! $this->allPassed())
            >
                Continue
            </button>
        </div>
    </div>
</div>