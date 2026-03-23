<div class="rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
    @include('installer::partials.navigation', ['steps' => $steps])

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">{{ static::title() }}</h2>
            <p class="mt-2 text-slate-600">
                {{  static::description()  }}
            </p>
        </div>

        @if ( $error !== null )
            <div class="rounded-2xl bg-slate-50 p-5 text-sm text-red-700 mb-5">
                <p>
                    {{ $error }}
                </p>
            </div>
        @endif
        <input type="hidden" wire:model="_token" />
        <div class="rounded-2xl bg-slate-50 p-5 text-sm text-slate-700 mb-5">
            <div class="flex-row flex gap-4">
                <div class="w-full column">
                    <label for="dbDriver" class="mr-2 text-sm font-medium text-slate-700">Database Driver:</label>
                    <select
                        required
                        wire:model="dbDriver"
                        class="block w-full cursor-pointer appearance-none rounded border border-gray-300 bg-white px-4 py-3 pr-8 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="mysql">MySQL</option>
                        <option value="pgsql">PostgreSQL</option>
                        <option value="mariadb">MariaDB</option>
                    </select>
                </div>
                <div class="w-full column">
                    <label for="dbHost" class="mr-2 text-sm font-medium text-slate-700">Database Host:</label>
                    <input
                        type="text"
                        wire:model="dbHost"
                        class="block w-full rounded border border-gray-300 bg-white px-4 py-3 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="localhost"
                        required
                    />
                </div>
            </div>
            <div class="flex-row flex gap-4 mt-4">
                <div class="w-full column">
                    <label for="dbPort" class="mr-2 text-sm font-medium text-slate-700">Database Port:</label>
                    <input
                        type="text"
                        wire:model="dbPort"
                        class="block w-full rounded border border-gray-300 bg-white px-4 py-3 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="3306"
                        required
                    />
                </div>
                <div class="w-full column">
                    <label for="dbName" class="mr-2 text-sm font-medium text-slate-700">Database Name:</label>
                    <input
                        type="text"
                        wire:model="dbName"
                        class="block w-full rounded border border-gray-300 bg-white px-4 py-3 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="my_database"
                        required
                    />
                </div>
            </div>
            <div class="flex-row flex gap-4 mt-4">
                <div class="w-full column">
                    <label for="dbUsername" class="mr-2 text-sm font-medium text-slate-700">Database Username:</label>
                    <input
                        type="text"                            
                        wire:model="dbUsername"
                        class="block w-full rounded border border-gray-300 bg-white px-4 py-3 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="root"
                        required
                    />
                </div>
                <div class="w-full column">
                    <label for="dbPassword" class="mr-2 text-sm font-medium text-slate-700">Database Password:</label>
                    <input
                        type="password"
                        wire:model="dbPassword"
                        class="block w-full rounded border border-gray-300 bg-white px-4 py-3 leading-tight shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="password"
                    />
                </div>
            </div>
        </div>
        @if ($isConnected)
            <div class="flex justify-end">
                <button
                    type="button"
                    wire:click="startInstallation"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 cursor-pointer"
                >
                    <span wire:loading.remove>Connected. Start installation</span>
                    <span wire:loading>Testing...</span>
                </button>
            </div>
        @else
            <div class="flex justify-end">
                <button
                    type="button"
                    wire:click="connected"
                    class="inline-flex items-center rounded-xl bg-teal-400 px-5 py-3 text-sm font-semibold text-white cursor-pointer"
                >
                    <span>{{ $loading ? 'Testing...' : 'Test Connection' }}</span>
                </button>
            </div>
        @endif
    </div>
</div>