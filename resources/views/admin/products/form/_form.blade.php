@livewire('admin.products.product-create', ['productModel' => $product ?? null], key('product-create' . $product->id))
@livewire('admin.products.variants', ['productModel' => $product ?? null], key('variants' . $product->id))
