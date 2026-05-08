<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'route' => route('admin.categories.index'),
    ],
    [
        'name' => 'Crear Categoría',
    ],
]">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @include('admin.categories.form._form')
    </form>
</x-admin-layout>
