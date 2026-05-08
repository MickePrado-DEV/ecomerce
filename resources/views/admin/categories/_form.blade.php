@csrf

<div class="card">

    <x-validation-errors class="mb-4" />

    <div class="mb-4">
        <x-label class="mb-2" for="family_id" value="Seleccionar Familia" />

        <x-select class="form-control w-full" name="family_id" id="family_id">
            <option value="">Seleccione una familia</option>

            @foreach ($families as $family)
                <option value="{{ $family->id }}"
                    {{ (string) old('family_id', $category->family_id ?? '') === (string) $family->id ? 'selected' : '' }}>
                    {{ $family->name }}
                </option>
            @endforeach
        </x-select>
    </div>

    <div class="mb-4">
        <x-label class="mb-2" for="name" value="Nombre de la Categoría" />

        <x-input class="w-full" name="name" value="{{ old('name', $category->name ?? '') }}"
            label="Nombre de la Categoría" placeholder="Ej: Electrónica" />
    </div>

    <div class="flex justify-end">
        <x-button>
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            Guardar Categoría
        </x-button>
    </div>

</div>
