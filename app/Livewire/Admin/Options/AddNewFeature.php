<?php

namespace App\Livewire\Admin\Options;

use App\Models\Option;
use Livewire\Component;

class AddNewFeature extends Component
{

    public Option $option;
    public $newFeature = [
        'value' => "",
        'description' => ''
    ];

    public function addFeature()
    {

        $this->validate([
            'newFeature.value' => 'required',
            'newFeature.description' => 'required'
        ]);

        $this->option->features()->create($this->newFeature);

        $this->reset('newFeature');

        $this->dispatch('featureAdded');
    }
    public function render()
    {
        return view('livewire.admin.options.add-new-feature');
    }
}
