<div class="mb-6 rounded-2xl bg-slate-50 p-4">
    <div class="grid gap-2 md:grid-cols-{{ count($steps) }}">
        @foreach ($steps as $step)
            <button
                type="button"
                class="flex items-center gap-2 rounded-xl px-4 py-3 text-left text-sm font-medium transition
                    @if ($step->isCurrent()) bg-indigo-600 text-white
                    @elseif ($step->isPrevious()) bg-emerald-50 text-emerald-700 hover:bg-emerald-100
                    @else bg-white text-slate-500 border border-slate-200 @endif"
                @if ($step->isPrevious())
                    wire:click="{{ $step->show() }}"
                @endif
            >
                <span>{{ $step->label }}</span>
            </button>
        @endforeach
    </div>
</div>