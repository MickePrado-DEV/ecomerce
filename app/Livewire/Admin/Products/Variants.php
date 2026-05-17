<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Variants extends Component
{
    public bool $openModal = true;
    public Product $product;
    public Collection $options;
    public $variant = [
        'option_id' => '',
        'features' => [
            [
                'id' => '',
                'value' => '',
                'description' => ''
            ]
        ]
    ];

    public function mount(): void
    {
        $this->options = Option::all();
    }

    public function updatedVariantOptionId()
    {

        $this->variant['features'] = [
            [
                'id' => '',
                'value' => '',
                'description' => ''
            ]
        ];
    }

    #[Computed()]
    public function features()
    {

        return Feature::where('option_id', $this->variant['option_id'])->get();
    }

    public function addFeature()
    {

        $this->variant['features'][] = [
            'id' => '',
            'value' => '',
            'description' => ''
        ];
    }
    public function removeFeature(int $index)
    {
        unset($this->variant['features'][$index]);
        $this->variant['features'] = array_values($this->variant['features']);
    }
    public function save()
    {

        $this->product->options()->attach(
            $this->variant['option_id'],
            [
                'features' => $this->variant['features']
            ]
        );
    }
    public function featureChange(int $index)
    {
        $feature = Feature::find($this->variant['features'][$index]['id']);
        if ($feature) {
            $this->variant['features'][$index]['value'] = $feature->value;
            $this->variant['features'][$index]['description'] = $feature->description;
        }
    }


    public function render()
    {
        return view('livewire.admin.products.variants');
    }
}
