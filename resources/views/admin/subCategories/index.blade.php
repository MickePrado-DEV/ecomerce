<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Sub Categorías',
    ],
]">

    <x-slot name="action">
        <a href="{{ route('admin.subCategories.create') }}" class="btn btn-add-action  ">
            <i class="fa-solid fa-plus mr-2"></i> Crear Sub Categoría
        </a>
    </x-slot>

    <div class="relative overflow-x-auto bg-gray-900 shadow-md rounded-lg border border-gray-700">
        <table class="w-full text-sm text-left rtl:text-right text-gray-300">
            <thead class="text-xs uppercase bg-gray-800 border-b border-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">ID</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nombre</th>
                    <th scope="col" class="px-6 py-3 font-medium">Categoría</th>
                    <th scope="col" class="px-6 py-3 font-medium">Familia</th>

                    <th scope="col" class="px-6 py-3 font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subCategories as $subCategory)
                    <tr class="bg-gray-900 border-b border-gray-700 hover:bg-gray-800">
                        <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                            {{ $subCategory->id }}
                        </th>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $subCategory->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $subCategory->category->name ?? 'Sin categoría' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $subCategory->category->family->name ?? 'Sin familia' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.subCategories.edit', $subCategory) }}"
                                class="text-blue-400 hover:text-blue-300 mr-2">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button type="button" class="text-red-400 hover:text-red-300"
                                onclick="confirmDelete({{ $subCategory->id }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <form id="delete-form-{{ $subCategory->id }}"
                                action="{{ route('admin.subCategories.destroy', $subCategory) }}" method="POST"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-2 text-gray-500"></i>
                                <span class="text-lg font-semibold">No hay categorías registradas</span>
                                <span class="text-sm text-gray-500">Agrega una nueva categoría para comenzar</span>
                                <a href="{{ route('admin.categories.create') }}"
                                    class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-500">
                                    <i class="fa-solid fa-plus mr-2"></i> Crear Categoría
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subCategories->links() }}
    </div>


    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-admin-layout>
