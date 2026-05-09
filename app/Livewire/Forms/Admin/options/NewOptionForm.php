<?php

namespace App\Livewire\Forms\Admin\options;

use App\Models\Option;
use Livewire\Form;

class NewOptionForm extends Form
{
    public $id = null;
    public $name = '';
    public $type = 1;
    public $features = [
        ['value' => '', 'description' => '']
    ];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2',
            'features' => 'required|array|min:1',
        ];

        foreach ($this->features as $index => $feature) {
            if ($this->type == 1) {
                $rules["features.$index.value"] = 'required|string|max:255';
            } else {
                $rules["features.$index.value"] = ['required', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'];
            }
            $rules["features.$index.description"] = 'nullable|string|max:255';
        }
        return $rules;
    }

    public function validationAttributes()
    {
        $attributes = [
            'name' => 'nombre',
            'type' => 'tipo',
            'features' => 'características',
            'features.*.value' => 'valor de la característica',
            'features.*.description' => 'descripción de la característica',
        ];

        foreach ($this->features as $index => $feature) {
            $attributes["features.$index.value"] = "valor de la característica #" . ($index + 1);
            $attributes["features.$index.description"] = "descripción de la característica #" . ($index + 1);
        }

        return $attributes;
    }

    public function setOption(Option $option)
    {
        $this->id = $option->id;
        $this->name = $option->name;
        $this->type = $option->type;
        $this->features = $option->features->map(fn($f) => [
            'value' => $f->value,
            'description' => $f->description
        ])->toArray();
    }

    public function save()
    {
        $this->validate();

        if ($this->id) {
            $option = Option::find($this->id);
            $option->update(['name' => $this->name, 'type' => $this->type]);
            $option->features()->delete();
        } else {
            $option = Option::create(['name' => $this->name, 'type' => $this->type]);
        }

        foreach ($this->features as $feature) {
            $option->features()->create($feature);
        }

        $this->reset();
        $this->features = [['value' => '', 'description' => '']];
    }

    public function addFeature()
    {
        $this->features[] = ['value' => '', 'description' => ''];
    }

    public function removeFeature($index)
    {
        if (count($this->features) <= 1) return;
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }
}
