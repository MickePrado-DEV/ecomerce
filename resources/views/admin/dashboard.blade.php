<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
]">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}" />
                <div class="ml-4 flex-1">
                    <h2 class="text-lg font-semibold">Bienvenido,{{ auth()->user()->name }}</h2>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit"
                            class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-center">
            <h2 class="text-xl font-semibold">MICKE PRADO DEV.</ </h2>
        </div>

    </div>
</x-admin-layout>
