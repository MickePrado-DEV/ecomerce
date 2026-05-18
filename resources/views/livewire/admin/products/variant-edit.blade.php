<div class="max-w-3xl">
    <section class="rounded-lg bg-[#1f2937] shadow-lg overflow-hidden border border-gray-700">
        <header class="bg-[#111827] px-4 py-3 border-b border-gray-700">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                <div class="min-w-0">
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Producto</p>
                    <h1 class="text-sm font-bold text-white truncate">{{ $product->name }}</h1>
                </div>
                <div class="hidden sm:block h-8 w-px bg-gray-700"></div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach ($variant->features as $feature)
                        <span
                            class="inline-flex items-center gap-1 text-[10px] bg-gray-800 text-gray-300 px-2 py-0.5 rounded-full border border-gray-600">
                            <span class="text-gray-500">{{ $feature->option->name }}:</span>
                            @if ($feature->option->type == 2)
                                <span class="h-2.5 w-2.5 rounded-full border border-gray-700"
                                    style="background-color: {{ $feature->value }}"></span>
                            @endif
                            <span>{{ $feature->description ?: $feature->value }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </header>

        <div class="p-4">
            <x-validation-errors class="mb-3 text-sm" />

            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 grid grid-cols-2 gap-3 min-w-0">
                    <div class="col-span-2 sm:col-span-1">
                        <x-label class="text-gray-300 text-xs">Código (SKU)</x-label>
                        <x-input wire:model="sku" type="text"
                            class="w-full mt-0.5 text-sm py-1.5 bg-[#111827] border-gray-600 text-white font-mono" />
                        @error('sku')
                            <span class="text-red-400 text-[11px] mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <x-label class="text-gray-300 text-xs">Stock</x-label>
                        <x-input wire:model="stock" type="number" step="1" min="0" inputmode="numeric"
                            class="w-full mt-0.5 text-sm py-1.5 bg-[#111827] border-gray-600 text-white" />
                        @error('stock')
                            <span class="text-red-400 text-[11px] mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="shrink-0 sm:w-36">
                    <x-label class="text-gray-300 text-xs">Imagen</x-label>
                    <div
                        class="mt-0.5 h-28 w-full sm:w-36 rounded-lg border border-dashed border-gray-600 overflow-hidden bg-[#111827]">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Vista previa"
                                class="h-full w-full object-cover">
                        @else
                            <img src="{{ $variant->image }}" alt="{{ $variant->sku }}"
                                class="h-full w-full object-cover">
                        @endif
                    </div>
                    <input type="file" wire:model="image" accept="image/*"
                        class="mt-1 block w-full text-[10px] text-gray-500 file:mr-1 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500" />
                    <div wire:loading wire:target="image" class="text-[10px] text-blue-400">Subiendo...</div>
                    @error('image')
                        <span class="text-red-400 text-[11px] mt-0.5 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-3 border-t border-gray-700">
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-600 px-3 py-1.5 text-xs font-medium text-gray-300 hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    Volver
                </a>

                <x-button wire:click="save" wire:loading.attr="disabled" wire:target="save,image"
                    class="text-sm py-1.5 px-4 bg-blue-600 hover:bg-blue-700 text-white border-none">
                    <span wire:loading.remove wire:target="save">
                        <i class="fa-solid fa-floppy-disk mr-1.5 text-xs"></i>
                        Guardar
                    </span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </x-button>
            </div>
        </div>
    </section>
</div>
