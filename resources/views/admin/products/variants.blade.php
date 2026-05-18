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
        'name' => 'Editar variante',
    ],
]">
    @livewire('admin.products.variant-edit', ['product' => $product, 'variant' => $variant], key('variant-edit-' . $variant->id))
</x-admin-layout>
