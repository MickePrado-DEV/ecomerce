<div class="card">

    <x-validation-errors class="mb-4" />

    {{-- Selección de Familia --}}
    <div class="mb-4">
        <x-label class="mb-2" for="family_id" value="Seleccionar Familia" />

        <x-select class="w-full" id="family_id" wire:model.live="subCategory.family_id">
            <option value="">Seleccione una familia</option>
            @foreach ($families as $family)
                <option value="{{ $family->id }}">
                    {{ $family->name }}
                </option>
            @endforeach
        </x-select>

        @error('subCategory.family_id')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    {{-- Selección de Categoría --}}
    <div class="mb-4">
        <x-label class="mb-2" for="category_id" value="Seleccionar Categoría" />

        <x-select class="w-full" id="category_id" wire:model.live="subCategory.category_id"
            :disabled="empty($subCategory['family_id'])">
            <option value="">
                {{ empty($subCategory['family_id']) ? 'Primero selecciona una familia' : 'Seleccione una categoría' }}
            </option>

            @foreach ($this->categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </x-select>

        @error('subCategory.category_id')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    {{-- Nombre de la Subcategoría --}}
    <div class="mb-4">
        <x-label class="mb-2" for="name" value="Nombre de la Subcategoría" />

        <x-input class="w-full" id="name" wire:model="subCategory.name" placeholder="Ej: Smartphones" />

        @error('subCategory.name')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex justify-end">
        <x-button wire:click="save" wire:loading.attr="disabled">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            {{ $this->isEditing() ? 'Actualizar Subcategoría' : 'Guardar Subcategoría' }}
        </x-button>
    </div>
</div>
