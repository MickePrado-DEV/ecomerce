@php
    $productModel = $product ?? null;
    $productKey = $productModel?->id ?? 'new';
@endphp

<div class="mb-12">


    @livewire('admin.products.product-create', ['productModel' => $productModel], key('product-create-' . $productKey))
</div>

@livewire('admin.products.variants', ['product' => $productModel], key('variants-' . $productKey))
