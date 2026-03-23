<div class="rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
    @include('installer::partials.navigation', ['steps' => $steps])

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">{{ static::title() }}</h2>
            <p class="mt-2 text-slate-600">{{ static::description() }}</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ( $summary as $key => $value )
                <div class="rounded-2xl border border-slate-200 p-4">
                    <h3 class="font-semibold text-slate-900">{{ ucfirst(str_replace('-', ' ', $key)) }}</h3>
                    @if ( is_array( $value ) )
                        <ul class="mt-2 text-sm text-slate-600">
                            @foreach ( $value as $field => $fieldValue )
                                @if ( is_array( $fieldValue ) )
                                    <strong>{{ ucfirst(str_replace('-', ' ', $field)) }}:</strong>
                                    <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-100 p-2 text-xs text-slate-700">{{ json_encode( $fieldValue, JSON_PRETTY_PRINT ) }}</pre>
                                @else
                                    <li><strong>{{ ucfirst(str_replace('-', ' ', $field)) }}:</strong> {{ str_contains( strtolower( $field ), 'password' ) || str_contains( strtolower( $field ), 'token' ) ? str_repeat( '*', strlen( $fieldValue ) ) : $fieldValue }}</li>
                                @endif    
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-sm text-slate-600">{{ $value }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-800">
            Click finish to write the environment file, run migrations, create the admin user, and lock the installer.
        </div>

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
                wire:click="finish"
                class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Finish installation</span>
                <span wire:loading>Finalizing…</span>
            </button>
        </div>
    </div>
</div>