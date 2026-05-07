<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Familias',
        'route' => route('admin.families.index'),
    ],
    [
        'name' => 'Editar Familia',
    ],
]">

    <div class="card">
        <form action="{{ route('admin.families.update', $family) }}" method="POST">
            @csrf
            @method('PUT')
            @csrf
            <div class="mb-4">
                <x-label class="mb-2" for="name" value="Nombre de la Familia" />
                <x-input class="w-full" name="name" value="{{ old('name', $family->name) }}"
                    label="Nombre de la Familia" placeholder="Ej: Electrónica" />
            </div>
            <div class="flex justify-end">
                <x-button class="">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Actualizar Familia
                </x-button>
            </div>

        </form>
    </div>

</x-admin-layout>
