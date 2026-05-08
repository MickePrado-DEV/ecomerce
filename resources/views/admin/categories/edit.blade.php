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
        'name' => $category->name,
    ],
]">

    <div class="card">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @method('PUT')
            @include('admin.categories.form._form')

        </form>
    </div>

</x-admin-layout>
