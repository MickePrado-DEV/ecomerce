<div>
    <section class="rounded-lg  shadow-lg border border-gray-100">
        <header class="bg-[#111827] px-6 py-4 border-b border-gray-700 flex-shrink-0">
            <div class="flex justify-between items-center">
                <h1 class="text-lg font-bold text-white">Opciones del producto</h1>
                <x-button wire:click="$set('openModal', true)"
                    class="bg-blue-600 hover:bg-blue-700 text-white border-none">
                    Agregar opción
                </x-button>
            </div>
        </header>
        <div class="p-6">
            @if ($this->attachedOptions->isNotEmpty())
                <ul class="space-y-6">
                    @foreach ($this->attachedOptions as $option)
                        <div wire:key="attached-option-{{ $option->id }}"
                            class="rounded-lg border border-gray-200  px-4 py-2 text-sm text-gray-700 relative">
                            <div class="absolute -top-3 left-4 px-2 bg-[#1f2937] ">
                                <button type="button"
                                    class="text-red-500 hover:text-red-400 disabled:opacity-40 disabled:cursor-not-allowed">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <span class="ml-2">
                                    {{ $option->name }}
                                </span>
                            </div>

                            <div class="flex flex-wrap ">
                                @foreach ($option->pivot->features as $feature)
                                    @if ($option->type == 1)
                                        <span
                                            class="group inline-flex items-center gap-1.5 bg-gray-800 text-gray-200 text-xs font-medium px-3 py-1 rounded-full border border-gray-600 hover:bg-gray-700 transition-colors">

                                            <span class="truncate">
                                                {{ $feature['description'] }}
                                            </span>

                                            {{-- Botón Eliminar Feature Tipo 1 --}}
                                            <button type="button"
                                                onclick="window.triggerDeleteFeature({{ $feature['id'] }}, {{ $option->pivot->features->count() }})"
                                                class="opacity-60 group-hover:opacity-100 transition-opacity focus:outline-none"
                                                title="Eliminar">
                                                <i class="fa-solid fa-xmark text-[10px] hover:text-red-400"></i>
                                            </button>
                                        </span>
                                    @else
                                        <div
                                            class="group inline-flex items-center gap-2 bg-gray-800 px-2.5 py-1 rounded-full border border-gray-600 hover:bg-gray-700 transition-colors">

                                            <span class="h-4 w-4 rounded-full border border-gray-900 shadow-inner"
                                                style="background-color: {{ $feature['value'] }}">
                                            </span>

                                            <span class="text-xs text-gray-300 font-medium truncate max-w-[100px]">
                                                {{ $feature['description'] ?: $feature['value'] }}
                                            </span>

                                            {{-- Botón Eliminar Feature Tipo 2 --}}
                                            <button type="button"
                                                onclick="window.triggerDeleteFeature({{ $feature['id'] }}, {{ $option->pivot->features->count() }})"
                                                class="ml-1 inline-flex items-center justify-center h-5 w-5 rounded-full
                               text-gray-300/70 hover:text-red-400 hover:bg-red-500/10
                               opacity-60 group-hover:opacity-100 transition
                               focus:outline-none focus:ring-2 focus:ring-red-500/40"
                                                title="Eliminar">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-400 text-center py-4">No hay opciones configuradas para este producto.</p>
            @endif
        </div>
    </section>

    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title">
            <span class="text-white font-bold">Agregar opción al producto</span>
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <x-label>Opción</x-label>
                <x-select class="w-full" wire:model.live="variant.option_id">
                    <option value="">Selecciona una opción</option>
                    @foreach ($options as $option)
                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                    @endforeach
                </x-select>
                @error('variant.option_id')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-4 mb-8">
                <hr class="flex-1 border-gray-600">
                <span class="text-gray-500 text-xs font-bold uppercase">Valores</span>
                <hr class="flex-1 border-gray-600">
            </div>

            <ul class="mb-4 space-y-4">
                @foreach ($variant['features'] as $index => $feature)
                    <li wire:key="variant-feature-{{ $index }}"
                        class="border border-gray-200 rounded-lg p-6 relative">
                        <div class="absolute -top-3 left-4 px-2 bg-[#1f2937] ">
                            <button type="button" wire:click="removeFeature({{ $index }})"
                                class="text-red-500 hover:text-red-400 disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>

                        <div>
                            <x-label class="mb-1">Valor</x-label>
                            <x-select class="w-full" wire:model.live="variant.features.{{ $index }}.id"
                                wire:change="featureChange({{ $index }})">
                                <option value="">Selecciona un valor</option>
                                @foreach ($this->features as $featureOption)
                                    <option value="{{ $featureOption->id }}">
                                        {{ $featureOption->description ?: $featureOption->value }}
                                    </option>
                                @endforeach
                            </x-select>
                            @error('variant.features.' . $index . '.id')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="flex justify-end">
                <x-button type="button" wire:click="addFeature">Agregar valor</x-button>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-danger-button wire:click="$set('openModal', false)">Cancelar</x-danger-button>
            <x-button wire:click="save" class="ml-2">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
