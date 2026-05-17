@props(['breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }"
    :class="{ 'overflow-hidden': sidebarOpen }">

    <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 z-30 transition-opacity sm:hidden" x-show="sidebarOpen"
        style="display: none" x-on:click="sidebarOpen = false">
    </div>

    @include('layouts.partials.admin.navigation')

    @include('layouts.partials.admin.sidebar')

    <div class="p-4 sm:ml-64">
        <div class="mt-14">
            <div class="flex justify-between items-center">
                @include('layouts.partials.admin.breadcrumb')
                @isset($action)
                    <div>
                        {{ $action }}
                    </div>
                @endisset
            </div>

            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', (event) => {
                const data = event[0];
                Swal.fire({
                    icon: data.icon || 'success',
                    title: data.title || '¡Éxito!',
                    text: data.text || data.message, // Acepta 'text' o 'message'
                    confirmButtonColor: '#3b82f6', // Azul de Tailwind
                });
            });
        });
    </script>

    @if (session('swal'))
        <script>
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif

    @stack('js')

</body>

</html>
