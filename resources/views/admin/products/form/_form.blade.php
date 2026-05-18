@php
    $productModel = $product ?? null;
    $productKey = $productModel?->id ?? 'new';
@endphp

<div class="space-y-8">
    @livewire('admin.products.product-create', ['productModel' => $productModel], key('product-create-' . $productKey))

    @if ($productModel)
        @livewire('admin.products.variants', ['productModel' => $productModel], key('variants-' . $productKey))
    @endif
</div>
