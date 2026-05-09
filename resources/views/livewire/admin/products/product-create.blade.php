<div class="card p-6">
    <x-validation-errors class="mb-4" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Columna Izquierda: Datos --}}
        <div>
            <div class="mb-4">
                <x-label>Código (SKU)</x-label>
                <x-input wire:model="product.sku" type="text" class="w-full" />
            </div>

            <div class="mb-4">
                <x-label>Nombre</x-label>
                <x-input wire:model="product.name" type="text" class="w-full" />
            </div>

            <div class="mb-4">
                <x-label>Precio</x-label>
                <x-input wire:model="product.price" type="number" step="0.01" class="w-full" />
            </div>

            {{-- Selects Jerárquicos --}}
            <div class="mb-4">
                <x-label>Familia</x-label>
                <x-select wire:model.live="family_id" class="w-full">
                    <option value="">Seleccione...</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="mb-4">
                <x-label>Categoría</x-label>
                <x-select wire:model.live="category_id" class="w-full" :disabled="empty($family_id)">
                    <option value="">Seleccione...</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="mb-4">
                <x-label>Subcategoría</x-label>
                <x-select wire:model="product.sub_category_id" class="w-full" :disabled="empty($category_id)">
                    <option value="">Seleccione...</option>
                    @foreach ($this->subcategories as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>

        {{-- Columna Derecha: Imagen y Descripción --}}
        <div>
            <div class="mb-4">
                <x-label>Imagen del Producto</x-label>
                <div
                    class="relative w-full h-64 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center bg-gray-50">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                    @elseif($product['image_path'])
                        <img src="{{ Storage::url($product['image_path']) }}" class="object-cover w-full h-full">
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fa-solid fa-image text-4xl mb-2"></i>
                            <p>Sin imagen seleccionada</p>
                        </div>
                    @endif
                </div>
                <input type="file" wire:model="image"
                    class="mt-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            </div>

            <div class="mb-4">
                <x-label>Descripción</x-label>
                <x-textarea wire:model="product.description" class="w-full" rows="4" />
            </div>
        </div>
    </div>

    <div class="flex justify-end mt-6">
        <x-button wire:click="save">
            {{ $product_id ? 'Actualizar Producto' : 'Guardar Producto' }}
        </x-button>
    </div>
</div>
