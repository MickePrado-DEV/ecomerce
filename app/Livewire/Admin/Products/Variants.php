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
    public bool $openModal = false;

    public int $productId;

    public Collection $options;

    public array $variant = [
        'option_id' => '',
        'features' => [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ],
    ];

    public function mount(Product $productModel): void
    {
        $this->productId = $productModel->id;
        $this->options = Option::all();
    }

    public function updatedVariantOptionId(): void
    {
        $this->variant['features'] = [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ];
    }

    #[Computed]
    public function features(): Collection
    {
        if (empty($this->variant['option_id'])) {
            return collect();
        }

        return Feature::where('option_id', $this->variant['option_id'])->get();
    }

    #[Computed]
    public function attachedOptions(): Collection
    {
        return Product::with('options')->find($this->productId)?->options ?? collect();
    }

    public function addFeature(): void
    {
        $this->variant['features'][] = [
            'id' => '',
            'value' => '',
            'description' => '',
        ];
    }

    public function removeFeature(int $index): void
    {
        unset($this->variant['features'][$index]);
        $this->variant['features'] = array_values($this->variant['features']);
    }

    public function save(): void
    {
        $this->validate([
            'variant.option_id' => 'required|exists:options,id',
            'variant.features' => 'required|array|min:1',
            'variant.features.*.id' => 'required|exists:features,id',
        ]);

        Product::findOrFail($this->productId)->options()->attach(
            $this->variant['option_id'],
            ['features' => $this->variant['features']]
        );

        unset($this->attachedOptions);

        $this->resetVariantForm();
        $this->openModal = false;
    }

    public function featureChange(int $index): void
    {
        $feature = Feature::find($this->variant['features'][$index]['id']);

        if ($feature) {
            $this->variant['features'][$index]['value'] = $feature->value;
            $this->variant['features'][$index]['description'] = $feature->description;
        }
    }

    private function resetVariantForm(): void
    {
        $this->variant = [
            'option_id' => '',
            'features' => [
                [
                    'id' => '',
                    'value' => '',
                    'description' => '',
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.products.variants');
    }
}
