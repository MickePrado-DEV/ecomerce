<div>
    <section class="rounded-lg bg-white shadow-lg border border-gray-100">
        <header class="bg-[#111827] px-6 py-4 border-b border-gray-700 flex-shrink-0">
            <div class="flex justify-between items-center">
                <h1 class="text-lg font-bold text-white">Opciones</h1>
                <x-button wire:click="set('openModal', true)"
                    class="bg-blue-600 hover:bg-blue-700 text-white border-none">
                    Crear Opción
                </x-button>
            </div>
        </header>
        <div class="p-6">

        </div>
    </section>
    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title">
            <span class="text-white font-bold">Crear Nueva Opción</span>
        </x-slot>
        <x-slot name="content">

            <div class="mb-4">
                <x-label>Opcion</x-label>

                <x-select class="w-full" wire:model.live="variant.option_id">
                    <option value="" disabled>Selecciona una opción</option>
                    @foreach ($options as $option)
                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                    @endforeach
                </x-select>
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
                        <div class="absolute -top-3 left-4 px-2 bg-[#1f2937]">
                            <button type="button" wire:click="removeFeature({{ $index }})" {{-- @disabled(count($newOption->features) <= 1) --}}
                                class="text-red-500 hover:text-red-400 disabled:opacity-40 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <div>
                            <x-label class="mb-1">Valores</x-label>
                            <x-select class="w-full" wire:model="variant.features[{{ $index }}].option_id"
                                wire:change="featureChange({{ $index }}))">
                                <option value="" disabled>Selecciona una opción</option>
                                @foreach ($this->features as $feature)
                                    <option value="{{ $feature->id }}">{{ $feature->description }}</option>
                                @endforeach
                            </x-select>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="flex justify-end">
                <x-button wire:click="addFeature">Agregar valor</x-button>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-danger-button wire:click="set('openModal', false)">Cancelar</x-danger-button>

            <x-button wire:click="save" class="ml-2">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>

</div>
