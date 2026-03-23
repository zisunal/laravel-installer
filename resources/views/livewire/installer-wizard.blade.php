<div class="overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200">
    <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ config( 'app.name' ) }} Installation Wizard</h1>
                <p class="mt-1 text-sm text-slate-600">Step {{ $currentStepIndex + 1 }} of {{ count($this->steps) }}</p>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-2 flex items-center justify-between text-xs text-slate-500">
                    <span>Progress</span>
                    <span>{{ $progressPercent }}%</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full rounded-full bg-indigo-600 transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="mt-5 grid gap-2 md:grid-cols-{{ count($this->steps) }}">
            @foreach ($this->steps as $index => $stepClass)
                <div class="rounded-2xl px-3 py-2 text-sm font-medium
                    @if ($index < $currentStepIndex) bg-emerald-50 text-emerald-700
                    @elseif ($index === $currentStepIndex) bg-indigo-50 text-indigo-700
                    @else bg-slate-100 text-slate-500 @endif">
                    {{ $stepClass::title() }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="p-6 md:p-8">
        @if ( $currentStepName )
            @livewire( $currentStepName, $currentStepState, key( $currentStepName ) )
        @endif
    </div>
</div>