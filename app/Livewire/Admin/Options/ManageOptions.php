<?php

namespace App\Livewire\Admin\Options;

use App\Livewire\Forms\Admin\options\NewOptionForm;
use App\Models\Option;
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
    #[On('addFeature')]
    public function loadOptions(): void
    {
        $this->options = Option::with('features')->get();
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

    public function save(): void
    {
        $isEditing = (bool) $this->newOption->id;

        $this->newOption->save();
        $this->loadOptions();
        $this->openModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => $isEditing
                ? 'Opción actualizada correctamente.'
                : 'Opción creada correctamente.',
        ]);
    }

    public function deleteOption(Option $option): void
    {
        $option->features()->delete();
        Option::whereKey($option->getKey())->delete();

        $this->loadOptions();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Eliminada!',
            'text' => 'La opción se eliminó correctamente.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
