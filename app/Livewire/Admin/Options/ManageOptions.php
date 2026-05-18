<?php

namespace App\Livewire\Admin\Options;

use App\Livewire\Forms\Admin\options\NewOptionForm;
use App\Models\Feature;
use App\Models\Option;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageOptions extends Component
{
    public NewOptionForm $newOption;

    public Collection $options;

    public bool $openModal = false;

    public function mount(): void
    {
        $this->loadOptions();
    }

    #[On('featureAdded')]
    public function loadOptions(): void
    {
        $this->options = Option::with('features')->orderBy('name')->get();
    }

    public function openCreateModal(): void
    {
        $this->newOption->reset();
        $this->newOption->features = [['value' => '', 'description' => '']];
        $this->resetValidation();
        $this->openModal = true;
    }

    public function editOption(Option $option): void
    {
        $this->resetValidation();
        $this->newOption->setOption($option);
        $this->openModal = true;
    }

    public function addFeature(): void
    {
        $this->newOption->addFeature();
    }

    public function removeFeature(int $index): void
    {
        $this->newOption->removeFeature($index);
    }

    public function deleteFeature(int $featureId): void
    {
        $feature = Feature::with('option.features')->findOrFail($featureId);

        if ($feature->option->features()->count() <= 1) {
            $this->dispatchSwal('error', 'Acción inválida', 'No puedes eliminar este valor porque la opción debe tener al menos un valor registrado.');

            return;
        }

        $feature->delete();
        $this->loadOptions();

        $this->dispatchSwal('success', '¡Eliminado!', 'El valor se eliminó correctamente.');
    }

    public function deleteOption(int $optionId): void
    {
        $option = Option::with('features')->findOrFail($optionId);

        try {
            $option->features()->delete();
            $option->delete();

            $this->loadOptions();

            $this->dispatchSwal('success', '¡Eliminada!', "La opción «{$option->name}» se eliminó correctamente.");
        } catch (QueryException) {
            $this->dispatchSwal(
                'error',
                'No se puede eliminar',
                'Esta opción está asociada a uno o más productos. Elimínala de los productos antes de borrarla.'
            );
        }
    }

    public function save(): void
    {
        $isEditing = (bool) $this->newOption->id;

        $this->newOption->save();
        $this->loadOptions();
        $this->openModal = false;

        $this->dispatchSwal(
            'success',
            '¡Éxito!',
            $isEditing ? 'Opción actualizada correctamente.' : 'Opción creada correctamente.'
        );
    }

    private function dispatchSwal(string $icon, string $title, string $text): void
    {
        $this->dispatch('swal', [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
