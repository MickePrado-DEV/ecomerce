<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => $product->name,
        'route' => route('admin.products.edit', $product),
    ],
    [
        'name' => $variant->features->pluck('description')->implode(' | '),
    ],
]">

    <figure>
        <img class="aspect[16/9] w-full object-cover object-center rounded-lg" src="{{ $variant->image }}" alt="">
    </figure>

</x-admin-layout>
