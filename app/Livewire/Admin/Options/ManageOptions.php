<?php

namespace App\Livewire\Admin\Options;

use App\Models\Option;
use App\Livewire\Forms\Admin\options\NewOptionForm;
use Livewire\Component;
use Illuminate\Support\Collection;

class ManageOptions extends Component
{
    public NewOptionForm $newOption; // Nombre clave para la vista
    public Collection $options;
    public bool $openModal = false;

    public function mount()
    {
        $this->loadOptions();
    }

    public function loadOptions()
    {
        $this->options = Option::with('features')->get();
    }

    public function openCreateModal()
    {
        $this->newOption->reset();
        $this->newOption->features = [['value' => '', 'description' => '']];
        $this->resetValidation();
        $this->openModal = true;
    }

    public function editOption(Option $option)
    {
        $this->resetValidation();
        $this->newOption->setOption($option);
        $this->openModal = true;
    }

    public function save()
    {
        $this->newOption->save();
        $this->loadOptions();
        $this->openModal = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Operación realizada correctamente.'
        ]);
    }

    public function deleteOption(Option $option)
    {
        $option->delete();
        $this->loadOptions();
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
