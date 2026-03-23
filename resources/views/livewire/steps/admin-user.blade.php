<div class="rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
    @include('installer::partials.navigation', ['steps' => $steps])

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">{{ static::title() }}</h2>
            <p class="mt-2 text-slate-600">{{ static::description() }}</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" wire:model.live="name" class="w-full rounded-xl border border-gray-300 px-5 py-3" />
                @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" wire:model.live="email" class="w-full rounded-xl border border-gray-300 px-5 py-3" />
                @error('email') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                <input type="password" wire:model.live="password" class="w-full rounded-xl border border-gray-300 px-5 py-3" />
                @error('password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Confirm Password</label>
                <input type="password" wire:model.live="password_confirmation" class="w-full rounded-xl border border-gray-300 px-5 py-3" />
                @error('password_confirmation') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
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
                wire:click="finalizeSetup"
                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 cursor-pointer"
            >
                Continue
            </button>
        </div>
    </div>
</div>