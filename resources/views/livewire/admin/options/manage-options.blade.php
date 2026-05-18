<div>
    {{-- Card Principal --}}
    <div class="rounded-lg bg-[#1f2937] shadow-lg overflow-hidden border border-gray-700 flex flex-col max-h-[80vh]">
        <header class="bg-[#111827] px-6 py-4 border-b border-gray-700 flex-shrink-0">
            <div class="flex justify-between items-center">
                <h1 class="text-lg font-bold text-white">Opciones</h1>
                <x-button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white border-none">
                    Crear Opción
                </x-button>
            </div>
        </header>

        {{-- AREA CON SCROLL: Lista de opciones --}}
        <div class="p-6 overflow-y-auto custom-scrollbar">

            <div class="space-y-8">
                @foreach ($options as $option)
                    <div class="relative border rounded-lg border-gray-600 p-6 pt-8 bg-[#242b3d]"
                        wire:key="option-{{ $option->id }}">

                        <div class="absolute -top-3 left-4 px-2 bg-[#1f2937]">
                            <span
                                class="text-sm font-semibold text-gray-300 uppercase tracking-wider">{{ $option->name }}</span>
                        </div>

                        <div class="absolute top-4 right-4 flex gap-3">
                            <button type="button" wire:click="editOption({{ $option->id }})"
                                class="text-gray-400 hover:text-blue-400">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            {{-- Botón Eliminar Opción Principal --}}
                            <button type="button"
                                onclick="confirmManageDeleteOption('{{ $this->getId() }}', {{ $option->id }}, @js($option->name))"
                                class="text-gray-400 hover:text-red-500">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>


                        <div class="flex flex-wrap gap-3 mt-3 mb-5">
                            @foreach ($option->features as $feature)
                                @if ($option->type == 1)
                                    <span
                                        class="group inline-flex items-center gap-1.5 bg-gray-800 text-gray-200 text-xs font-medium px-3 py-1 rounded-full border border-gray-600 hover:bg-gray-700 transition-colors">

                                        <span class="truncate">
                                            {{ $feature->description }}
                                        </span>

                                        {{-- Botón Eliminar Feature Tipo 1 --}}
                                        <button type="button"
                                            onclick="confirmManageDeleteFeature('{{ $this->getId() }}', {{ $feature->id }}, {{ $option->features->count() }})"
                                            @disabled($option->features->count() <= 1)
                                            class="opacity-60 group-hover:opacity-100 transition-opacity focus:outline-none disabled:opacity-30 disabled:cursor-not-allowed"
                                            title="Eliminar">
                                            <i class="fa-solid fa-xmark text-[10px] hover:text-red-400"></i>
                                        </button>
                                    </span>
                                @else
                                    <div
                                        class="group inline-flex items-center gap-2 bg-gray-800 px-2.5 py-1 rounded-full border border-gray-600 hover:bg-gray-700 transition-colors">

                                        <span class="h-4 w-4 rounded-full border border-gray-900 shadow-inner"
                                            style="background-color: {{ $feature->value }}">
                                        </span>

                                        <span class="text-xs text-gray-300 font-medium truncate max-w-[100px]">
                                            {{ $feature->description ?: $feature->value }}
                                        </span>

                                        {{-- Botón Eliminar Feature Tipo 2 --}}
                                        <button type="button"
                                            onclick="confirmManageDeleteFeature('{{ $this->getId() }}', {{ $feature->id }}, {{ $option->features->count() }})"
                                            @disabled($option->features->count() <= 1)
                                            class="ml-1 inline-flex items-center justify-center h-5 w-5 rounded-full
                               text-gray-300/70 hover:text-red-400 hover:bg-red-500/10
                               opacity-60 group-hover:opacity-100 transition
                               focus:outline-none focus:ring-2 focus:ring-red-500/40
                               disabled:opacity-30 disabled:cursor-not-allowed"
                                            title="Eliminar">
                                            <i class="fa-solid fa-xmark text-[10px]"></i>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>


                        <div>
                            @livewire('admin.options.add-new-feature', ['option' => $option], key('add-new-feature-' . $option->id))
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title">
            <span class="text-white font-bold">{{ $newOption->id ? 'Actualizar Opción' : 'Crear Nueva Opción' }}</span>
        </x-slot>

        <x-slot name="content">
            {{-- AREA CON SCROLL --}}
            <div class="max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                <x-validation-errors class="mb-4" />

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-label class="text-gray-300 mb-1">Nombre</x-label>
                        <x-input wire:model="newOption.name" type="text"
                            class="w-full bg-[#111827] border-gray-600 text-white" />
                    </div>

                    <div>
                        <x-label class="text-gray-300 mb-1">Tipo</x-label>
                        <x-select wire:model.live="newOption.type"
                            class="w-full bg-[#111827] border-gray-600 text-white">
                            <option value="1">Texto / Etiquetas</option>
                            <option value="2">Muestrario de Colores</option>
                        </x-select>
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-8">
                    <hr class="flex-1 border-gray-600">
                    <span class="text-gray-500 text-xs font-bold uppercase">Valores</span>
                    <hr class="flex-1 border-gray-600">
                </div>

                <div class="space-y-6">
                    @foreach ($newOption->features as $index => $feature)
                        {{-- IMPORTANTE: wire:key único para cada iteración --}}
                        <div class="p-5 rounded-lg border border-gray-600  bg-[#1f2937]/50 relative"
                            wire:key="feature-option-{{ $index }}">

                            <div class="absolute -top-3 left-4 px-2 bg-[#1f2937]">
                                <button type="button" wire:click="removeFeature({{ $index }})"
                                    @disabled(count($newOption->features) <= 1)
                                    class="text-red-500 hover:text-red-400 disabled:opacity-40 disabled:cursor-not-allowed">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <x-label class="text-[10px] text-gray-400 uppercase font-bold mb-1">Valor</x-label>
                                    @if ($newOption->type == 1)
                                        <x-input wire:model="newOption.features.{{ $index }}.value"
                                            type="text" class="w-full bg-[#111827] border-gray-600 text-white" />
                                    @else
                                        <div class="flex items-center gap-2">
                                            <input type="color"
                                                wire:model.live="newOption.features.{{ $index }}.value"
                                                class="h-9 w-12 p-0 border border-gray-600 rounded bg-transparent cursor-pointer">
                                            <x-input wire:model="newOption.features.{{ $index }}.value"
                                                type="text"
                                                class="flex-1 bg-[#111827] border-gray-600 text-white text-sm" />
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <x-label
                                        class="text-[10px] text-gray-400 uppercase font-bold mb-1">Descripción</x-label>
                                    <x-input wire:model="newOption.features.{{ $index }}.description"
                                        type="text" class="w-full bg-[#111827] border-gray-600 text-white" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Botón de añadir fuera del loop de features pero dentro del scroll --}}
                <div class="mt-6 flex justify-center pb-4">
                    <button type="button" wire:click="addFeature"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i>
                        AÑADIR OTRO VALOR
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$set('openModal', false)" class="bg-gray-700 text-white mr-2">CANCELAR</x-button>
            <x-button wire:click="save"
                class="bg-blue-600 text-white hover:bg-blue-700">{{ $newOption->id ? 'GUARDAR CAMBIOS' : 'CREAR OPCIÓN' }}</x-button>
        </x-slot>
    </x-dialog-modal>

</div>
