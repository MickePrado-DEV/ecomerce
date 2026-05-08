<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Sub Categorías',
        'route' => route('admin.subCategories.index'),
    ],
    [
        'name' => $subCategory->name,
    ],
]">
    @include('admin.subCategories.form._form')
</x-admin-layout>
