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
        window.swalDarkConfig = {
            background: '#1f2937',
            color: '#fff',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#ef4444',
            customClass: {
                popup: 'border border-gray-700 rounded-lg shadow-xl',
            },
        };

        window.callLivewireAfterConfirm = function(componentId, method, params, swalOptions) {
            return Swal.fire({
                ...window.swalDarkConfig,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                ...swalOptions,
            }).then((result) => {
                if (result.isConfirmed) {
                    const component = Livewire.find(componentId);

                    if (component) {
                        component.call(method, ...params);
                    }
                }
            });
        };

        window.confirmManageDeleteOption = function(componentId, optionId, name) {
            return window.callLivewireAfterConfirm(componentId, 'deleteOption', [optionId], {
                title: '¿Eliminar la opción?',
                html: `Se borrará <strong>«${name}»</strong> junto con todos sus valores de forma permanente.`,
                icon: 'warning',
                confirmButtonText: 'Sí, eliminar',
            });
        };

        window.confirmManageDeleteFeature = function(componentId, featureId, totalFeatures) {
            if (totalFeatures <= 1) {
                return Swal.fire({
                    ...window.swalDarkConfig,
                    title: 'Acción inválida',
                    text: 'No puedes eliminar este valor porque la opción debe tener al menos un valor registrado.',
                    icon: 'error',
                    confirmButtonText: 'Entendido',
                });
            }

            return window.callLivewireAfterConfirm(componentId, 'deleteFeature', [featureId], {
                title: '¿Eliminar este valor?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                confirmButtonText: 'Sí, eliminar valor',
            });
        };

        window.confirmProductDetachOption = function(componentId, optionId, name) {
            return window.callLivewireAfterConfirm(componentId, 'detachOption', [optionId], {
                title: '¿Quitar opción del producto?',
                html: `Se desvinculará <strong>«${name}»</strong> de este producto.`,
                icon: 'warning',
                confirmButtonText: 'Sí, quitar',
            });
        };

        window.confirmProductRemovePivotFeature = function(componentId, optionId, featureIndex, totalFeatures) {
            if (totalFeatures <= 1) {
                return Swal.fire({
                    ...window.swalDarkConfig,
                    title: 'Acción inválida',
                    text: 'La opción debe tener al menos un valor en este producto.',
                    icon: 'error',
                    confirmButtonText: 'Entendido',
                });
            }

            return window.callLivewireAfterConfirm(componentId, 'removePivotFeature', [optionId, featureIndex], {
                title: '¿Eliminar este valor?',
                text: 'Se quitará este valor de la opción en el producto.',
                icon: 'warning',
                confirmButtonText: 'Sí, eliminar valor',
            });
        };

        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', (payload) => {
                const data = Array.isArray(payload) ? payload[0] : payload;

                Swal.fire({
                    ...window.swalDarkConfig,
                    icon: data.icon || 'success',
                    title: data.title || '¡Éxito!',
                    text: data.text || data.message || '',
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
