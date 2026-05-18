<div class="space-y-8">
    {{-- Opciones asignadas al producto --}}
    <section class="rounded-lg bg-[#1f2937] shadow-lg overflow-hidden border border-gray-700 mb-12">
        <header class="bg-[#111827] px-6 py-4 border-b border-gray-700">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Opciones del producto</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Define qué atributos tendrá este producto (talla, color,
                        etc.)</p>
                </div>
                <x-button wire:click="$set('openModal', true)"
                    class="bg-blue-600 hover:bg-blue-700 text-white border-none shrink-0">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Agregar opción
                </x-button>
            </div>
        </header>

        <div class="p-6">
            @if ($this->attachedOptions->isNotEmpty())
                <div class="space-y-6">
                    @foreach ($this->attachedOptions as $option)
                        <article wire:key="attached-option-{{ $option->id }}"
                            class="relative rounded-lg border border-gray-600 bg-[#242b3d] p-5 pt-9">
                            <div class="absolute -top-3 left-4 flex items-center gap-2 bg-[#242b3d] px-2">
                                <span class="text-sm font-semibold text-gray-200 uppercase tracking-wide">
                                    {{ $option->name }}
                                </span>
                                <span
                                    class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full
                                    {{ $option->type == 1 ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-purple-500/20 text-purple-300 border border-purple-500/30' }}">
                                    {{ $option->type == 1 ? 'Texto' : 'Color' }}
                                </span>
                            </div>

                            <div class="absolute top-4 right-4">
                                <button type="button"
                                    onclick="confirmProductDetachOption('{{ $this->getId() }}', {{ $option->id }}, @js($option->name))"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400
                                           hover:text-red-400 hover:bg-red-500/10 transition-colors"
                                    title="Quitar opción del producto">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>

                            <div class="flex flex-wrap gap-2 mt-1 min-h-[2rem]">
                                @foreach ($option->pivot->features as $featureIndex => $feature)
                                    <div wire:key="option-{{ $option->id }}-feature-{{ $feature['id'] }}">
                                        @if ($option->type == 1)
                                            <span
                                                class="group inline-flex items-center gap-1.5 bg-gray-800 text-gray-200 text-xs font-medium
                                                   px-3 py-1.5 rounded-full border border-gray-600 hover:bg-gray-700 transition-colors">
                                                <span class="truncate max-w-[140px]">
                                                    {{ $feature['description'] ?: $feature['value'] }}
                                                </span>
                                                <button type="button"
                                                    onclick="confirmProductRemovePivotFeature('{{ $this->getId() }}', {{ $option->id }}, {{ $featureIndex }}, {{ count($option->pivot->features) }})"
                                                    @disabled(count($option->pivot->features) <= 1)
                                                    class="inline-flex h-4 w-4 items-center justify-center rounded-full opacity-60
                                                       group-hover:opacity-100 hover:text-red-400 hover:bg-red-500/20
                                                       disabled:opacity-30 disabled:cursor-not-allowed"
                                                    title="Eliminar valor">
                                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                                </button>
                                            </span>
                                        @else
                                            <span
                                                class="group inline-flex items-center gap-2 bg-gray-800 text-gray-200 text-xs font-medium
                                                   px-2.5 py-1.5 rounded-full border border-gray-600 hover:bg-gray-700 transition-colors">
                                                <span
                                                    class="h-4 w-4 shrink-0 rounded-full border border-gray-900 shadow-inner ring-1 ring-white/10"
                                                    style="background-color: {{ $feature['value'] }}"></span>
                                                <span class="truncate max-w-[100px] text-gray-300">
                                                    {{ $feature['description'] ?: $feature['value'] }}
                                                </span>
                                                <button type="button"
                                                    onclick="confirmProductRemovePivotFeature('{{ $this->getId() }}', {{ $option->id }}, {{ $featureIndex }}, {{ count($option->pivot->features) }})"
                                                    @disabled(count($option->pivot->features) <= 1)
                                                    class="inline-flex h-5 w-5 items-center justify-center rounded-full opacity-60
                                                       group-hover:opacity-100 hover:text-red-400 hover:bg-red-500/10
                                                       disabled:opacity-30 disabled:cursor-not-allowed"
                                                    title="Eliminar valor">
                                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                                </button>
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <p class="mt-3 text-[10px] text-gray-500">
                                {{ count($option->pivot->features) }}
                                {{ count($option->pivot->features) === 1 ? 'valor' : 'valores' }}
                            </p>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div
                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-800 border border-gray-600">
                        <i class="fa-solid fa-sliders text-lg text-gray-500"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-300">Sin opciones configuradas</p>
                    <p class="text-xs text-gray-500 mt-1 max-w-xs">
                        Agrega opciones antes de crear variantes de venta.
                    </p>
                    <button type="button" wire:click="$set('openModal', true)"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-500 transition-colors">
                        <i class="fa-solid fa-plus"></i>
                        Agregar primera opción
                    </button>
                </div>
            @endif
        </div>
    </section>

    <section class="rounded-lg bg-[#1f2937] shadow-lg overflow-hidden border border-gray-700">
        <header class="bg-[#111827] px-6 py-4 border-b border-gray-700">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Variantes del Producto</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Listado de variantes del producto</p>
                </div>

            </div>
        </header>

        <div class="p-6">
            <ul class="divide-y -my-4 divide-gray-700">
                @foreach ($product->variants as $itemVariant)
                    <li class="py-4">
                        <img src="{{ $itemVariant->img }}" class="w-12 h-12 object-cover object-center" alt="">
                        <p class="divide-x">

                            @foreach ($itemVariant->features as $feature)
                                <span class="px-3">
                                    {{ $feature->description }}
                                </span>
                            @endforeach
                        </p>
                        <a href="{{ route('admin.products.variants', [$product, $itemVariant]) }}"
                            class="ml-auto btn btn-blue">
                            editar
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>



    </section>

    {{-- Modal: agregar opción al producto --}}
    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title">
            <span class="text-white font-bold">Agregar opción al producto</span>
        </x-slot>

        <x-slot name="content">
            <div class="max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                <div class="mb-4">
                    <x-label class="text-gray-300 mb-1">Opción</x-label>
                    <x-select wire:model.live="optionForm.option_id"
                        class="w-full bg-[#111827] border-gray-600 text-white">
                        <option value="">Selecciona una opción</option>
                        @foreach ($options as $option)
                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                        @endforeach
                    </x-select>
                    @error('optionForm.option_id')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <hr class="flex-1 border-gray-600">
                    <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Valores disponibles</span>
                    <hr class="flex-1 border-gray-600">
                </div>

                <ul class="mb-4 space-y-4">
                    @foreach ($optionForm['features'] as $index => $feature)
                        <li wire:key="option-form-feature-{{ $index }}"
                            class="relative rounded-lg border border-gray-600 bg-[#1f2937]/80 p-5 pt-8">
                            <div class="absolute -top-3 left-4 bg-[#1f2937] px-2">
                                <button type="button" wire:click="removeOptionFormFeature({{ $index }})"
                                    @disabled(count($optionForm['features']) <= 1)
                                    class="text-red-500 hover:text-red-400 disabled:opacity-40 disabled:cursor-not-allowed">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div>
                                <x-label class="text-[10px] text-gray-400 uppercase font-bold mb-1">Valor</x-label>
                                <x-select wire:model.live="optionForm.features.{{ $index }}.id"
                                    wire:change="optionFormFeatureChange({{ $index }})"
                                    class="w-full bg-[#111827] border-gray-600 text-white" :disabled="empty($optionForm['option_id'])">
                                    <option value="">Selecciona un valor</option>
                                    @foreach ($this->optionFormFeatures as $featureOption)
                                        <option value="{{ $featureOption->id }}">
                                            {{ $featureOption->description ?: $featureOption->value }}
                                        </option>
                                    @endforeach
                                </x-select>
                                @error('optionForm.features.' . $index . '.id')
                                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="flex justify-center pb-2">
                    <button type="button" wire:click="addOptionFormFeature"
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-600 hover:bg-emerald-700
                               text-white text-xs font-bold py-2 px-4 transition-colors shadow-sm">
                        <i class="fa-solid fa-plus-circle"></i>
                        Añadir otro valor
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$set('openModal', false)" class="bg-gray-700 text-white mr-2">Cancelar</x-button>
            <x-button wire:click="saveOption" class="bg-blue-600 text-white hover:bg-blue-700">
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Guardar opción
            </x-button>
        </x-slot>
    </x-dialog-modal>


</div>
