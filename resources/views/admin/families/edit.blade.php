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
        'name' => $family->name,
    ],
]">


    <form action="{{ route('admin.families.update', $family) }}" method="POST">

        @method('PUT')

        @include('admin.families._form')
    </form>
</x-admin-layout>
