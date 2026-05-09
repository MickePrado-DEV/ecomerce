<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
    ],
]">

    <x-slot name="action">
        <a href="{{ route('admin.products.create') }}" class="btn btn-add-action  ">
            <i class="fa-solid fa-plus mr-2"></i> Crear Producto
        </a>
    </x-slot>

    <div class="relative overflow-x-auto bg-gray-900 shadow-md rounded-lg border border-gray-700">
        <table class="w-full text-sm text-left rtl:text-right text-gray-300">
            <thead class="text-xs uppercase bg-gray-800 border-b border-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">ID</th>
                    <th scope="col" class="px-6 py-3 font-medium">Sku</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nombre</th>
                    <th scope="col" class="px-6 py-3 font-medium">Precio</th>

                    <th scope="col" class="px-6 py-3 font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="bg-gray-900 border-b border-gray-700 hover:bg-gray-800">
                        <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                            {{ $product->id }}
                        </th>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $product->sku }}
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $product->price }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-blue-400 hover:text-blue-300 mr-2">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button type="button" class="text-red-400 hover:text-red-300"
                                onclick="confirmDelete({{ $product->id }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <form id="delete-form-{{ $product->id }}"
                                action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
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
                                <span class="text-lg font-semibold">No hay productos registrados</span>
                                <span class="text-sm text-gray-500">Agrega un nuevo producto para comenzar</span>
                                <a href="{{ route('admin.products.create') }}"
                                    class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-500">
                                    <i class="fa-solid fa-plus mr-2"></i> Crear Producto
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
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
