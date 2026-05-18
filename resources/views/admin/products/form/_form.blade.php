@php
    $productModel = $product ?? null;
    $productKey = $productModel?->id ?? 'new';
@endphp

<div class="mb-12">
    @livewire('admin.products.product-create', ['productModel' => $productModel], key('product-create-' . $productKey))
</div>

@if ($productModel)
    @livewire('admin.products.variants', ['productModel' => $productModel], key('variants-' . $productKey))
@endif
