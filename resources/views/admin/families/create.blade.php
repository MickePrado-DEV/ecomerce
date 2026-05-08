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
        'name' => 'Crear Familia',
    ],
]">
    <form action="{{ route('admin.families.store') }}" method="POST">
        @include('admin.families.form._form')
    </form>
</x-admin-layout>
