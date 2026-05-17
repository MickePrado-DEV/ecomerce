<div>
    {{-- The whole world belongs to you. --}}
    <form wire:submit="addFeature" class="flex flex-wrap gap-4 mt-2 mb-4 space-x-4">

        <div class="flex-1">
            <div>
                <x-label class="text-[10px] text-gray-400 uppercase font-bold mb-1">Valor</x-label>
                @if ($option->type == 1)
                    <x-input wire:model="newFeature.value" type="text"
                        class="w-full bg-[#111827] border-gray-600 text-white" />
                @else
                    <div class="flex items-center gap-2">
                        <input type="color" wire:model.live="newFeature.value"
                            class="h-9 w-12 p-0 border border-gray-600 rounded bg-transparent cursor-pointer">
                        <x-input wire:model="newFeature.value" type="text"
                            class="flex-1 bg-[#111827] border-gray-600 text-white text-sm" />
                    </div>
                @endif
            </div>
        </div>
        <div class="flex-1">
            <x-label class="text-[10px] text-gray-400 uppercase font-bold mb-1">Descripción</x-label>
            <x-input wire:model="newFeature.description" type="text"
                class="w-full bg-[#111827] border-gray-600 text-white" />
        </div>
        <div class="pt-7">
            <x-button>
                Agregar

            </x-button>
        </div>
    </form>
</div>
