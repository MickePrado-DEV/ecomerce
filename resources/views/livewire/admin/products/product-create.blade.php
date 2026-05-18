<div class="rounded-lg bg-[#1f2937] shadow-lg overflow-hidden border border-gray-700">
    <header class="bg-[#111827] px-6 py-4 border-b border-gray-700">
        <h2 class="text-lg font-bold text-white">
            {{ $product_id ? 'Datos del producto' : 'Nuevo producto' }}
        </h2>
        <p class="text-xs text-gray-400 mt-0.5">
            {{ $product_id ? 'Información general y clasificación' : 'Completa la información para crear el producto' }}
        </p>
    </header>

    <div class="p-6">
        <x-validation-errors class="mb-4" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <x-label class="text-gray-300">Código (SKU)</x-label>
                    <x-input wire:model="product.sku" type="text"
                        class="w-full mt-1 bg-[#111827] border-gray-600 text-white" />
                </div>

                <div>
                    <x-label class="text-gray-300">Nombre</x-label>
                    <x-input wire:model="product.name" type="text"
                        class="w-full mt-1 bg-[#111827] border-gray-600 text-white" />
                </div>

                <div>
                    <x-label class="text-gray-300">Precio</x-label>
                    <x-input wire:model="product.price" type="number" step="0.01"
                        class="w-full mt-1 bg-[#111827] border-gray-600 text-white" />
                </div>

                @if ($this->showStockField)
                    <div wire:key="product-stock-field"
                        class="rounded-lg border border-emerald-500/30 bg-emerald-500/5 p-4">
                        <x-label class="text-gray-200">Stock del producto</x-label>
                        <x-input wire:model="product.stock" type="number" step="1" min="0" inputmode="numeric"
                            class="w-full mt-1 bg-[#111827] border-gray-600 text-white" />
                        <p class="text-xs text-gray-400 mt-1.5">
                            Solo aplica mientras el producto no tenga opciones ni variantes.
                        </p>
                    </div>
                @elseif ($product_id)
                    <div wire:key="product-stock-hidden-notice"
                        class="rounded-lg border border-amber-500/30 bg-amber-500/5 px-4 py-3">
                        <p class="text-xs text-amber-200/90 flex items-start gap-2">
                            <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                            <span>El inventario se gestiona por variante. El stock general ya no aplica.</span>
                        </p>
                    </div>
                @endif

                <div>
                    <x-label class="text-gray-300">Familia</x-label>
                    <x-select wire:model.live="family_id" class="w-full mt-1 bg-[#111827] border-gray-600 text-white">
                        <option value="">Seleccione...</option>
                        @foreach ($families as $family)
                            <option value="{{ $family->id }}">{{ $family->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <x-label class="text-gray-300">Categoría</x-label>
                    <x-select wire:model.live="category_id" class="w-full mt-1 bg-[#111827] border-gray-600 text-white"
                        :disabled="empty($family_id)">
                        <option value="">Seleccione...</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <x-label class="text-gray-300">Subcategoría</x-label>
                    <x-select wire:model="product.sub_category_id"
                        class="w-full mt-1 bg-[#111827] border-gray-600 text-white" :disabled="empty($category_id)">
                        <option value="">Seleccione...</option>
                        @foreach ($this->subcategories as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <x-label class="text-gray-300">Imagen del producto</x-label>
                    <div
                        class="relative mt-1 w-full h-56 rounded-lg border-2 border-dashed border-gray-600 overflow-hidden flex items-center justify-center bg-[#111827]">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Vista previa"
                                class="object-cover w-full h-full">
                        @elseif ($product['image_path'])
                            <img src="{{ Storage::url($product['image_path']) }}" alt="{{ $product['name'] }}"
                                class="object-cover w-full h-full">
                        @else
                            <div class="text-center text-gray-500">
                                <i class="fa-solid fa-image text-3xl mb-2"></i>
                                <p class="text-sm">Sin imagen</p>
                            </div>
                        @endif
                    </div>
                    <input type="file" wire:model="image"
                        class="mt-2 block w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500" />
                    <div wire:loading wire:target="image" class="text-xs text-blue-400 mt-1">Subiendo imagen...
                    </div>
                </div>

                <div>
                    <x-label class="text-gray-300">Descripción</x-label>
                    <x-textarea wire:model="product.description"
                        class="w-full mt-1 bg-[#111827] border-gray-600 text-white" rows="5" />
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-8 pt-6 border-t border-gray-700">
            <x-button wire:click="save" wire:loading.attr="disabled" wire:target="save,image"
                class="bg-blue-600 hover:bg-blue-700 text-white border-none">
                <span wire:loading.remove wire:target="save">
                    {{ $product_id ? 'Actualizar producto' : 'Guardar producto' }}
                </span>
                <span wire:loading wire:target="save">Guardando...</span>
            </x-button>
        </div>
    </div>
</div>
