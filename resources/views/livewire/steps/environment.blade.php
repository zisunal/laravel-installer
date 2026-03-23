<div class="rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
    @include('installer::partials.navigation', ['steps' => $steps])

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">{{ static::title() }}</h2>
            <p class="mt-2 text-slate-600">{{ static::description() }}</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">App Name</label>
                <input type="text" wire:model.live="app_name" class="w-full rounded-xl border border-gray-300 px-5 py-4" />
                @error('app_name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">App URL</label>
                <input type="url" wire:model.live="app_url" class="w-full rounded-xl border border-gray-300 px-5 py-4" placeholder="https://example.com" />
                @error('app_url') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Environment</label>
                <select
                    wire:model.live="app_env"
                    class="block w-full cursor-pointer appearance-none rounded border border-gray-300 bg-white px-4 py-3 pr-8 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="local">Local</option>
                    <option value="production">Production</option>
                    <option value="staging">Staging</option>
                    <option value="testing">Testing</option>
                </select>
                @error('app_env') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Locale</label>
                <input type="text" wire:model.live="app_locale" class="w-full rounded-xl border border-gray-300 px-5 py-3" />
                @error('app_locale') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Server Timezone</label>
                <input type="text" wire:model.live="app_timezone" class="w-full rounded-xl border border-gray-300 px-5 py-3" />
                @error('app_timezone') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <button
                type="button"
                wire:click="previousStep"
                class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Back
            </button>

            <button
                type="button"
                wire:click="enSetup"
                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 cursor-pointer"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Continue</span>
                <span wire:loading>Saving…</span>
            </button>
        </div>
    </div>
</div>