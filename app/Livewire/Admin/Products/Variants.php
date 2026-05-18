<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature;
use App\Models\Option;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Variants extends Component
{
    public bool $openModal = false;
    public int $productId;
    public Product $product;
    public Collection $options;

    public array $optionForm = [
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
        $this->options = Option::orderBy('name')->get();
    }

    /**
     * Genera automáticamente todas las combinaciones posibles de variantes
     */
    public function generateVariants(): void
    {
        $product = Product::findOrFail($this->productId);

        // 1. Obtener todas las variantes existentes para limpiar imágenes si aplica
        $existingVariants = Variant::where('product_id', $this->productId)->get();
        foreach ($existingVariants as $variant) {
            if ($variant->image_path) {
                Storage::disk('public')->delete($variant->image_path);
            }
            $variant->features()->detach();
            $variant->delete();
        }

        // 2. Obtener las características de las opciones asignadas al producto
        $features = $product->options->pluck('pivot.features');

        if ($features->isEmpty()) {
            return;
        }

        // 3. Generar las combinaciones usando la función recursiva
        $combinations = $this->generateCombinations($features->toArray());

        // 4. Crear las nuevas variantes mapeadas
        foreach ($combinations as $combination) {
            $variant = Variant::create([
                'product_id' => $this->productId,
            ]);

            // Asumiendo una tabla pivote entre Variant y Feature
            $variant->features()->attach($combination);
        }
    }

    private function generateCombinations(array $arrayData, int $index = 0, array $combination = []): array
    {
        if ($index == count($arrayData)) {
            return [$combination];
        }

        $result = [];

        foreach ($arrayData[$index] as $item) {
            $tempCombination = $combination;
            $tempCombination[] = $item['id'];

            $result = array_merge(
                $result,
                $this->generateCombinations($arrayData, $index + 1, $tempCombination)
            );
        }

        return $result;
    }

    public function updatedOptionFormOptionId(): void
    {
        $this->optionForm['features'] = [
            [
                'id' => '',
                'value' => '',
                'description' => '',
            ],
        ];
    }

    #[Computed]
    public function optionFormFeatures(): Collection
    {
        if (empty($this->optionForm['option_id'])) {
            return collect();
        }

        return Feature::where('option_id', $this->optionForm['option_id'])->get();
    }

    #[Computed]
    public function attachedOptions(): Collection
    {
        return Product::with('options')->find($this->productId)?->options ?? collect();
    }

    #[Computed]
    public function productVariants(): Collection
    {
        return Product::with(['variants.features.option'])
            ->find($this->productId)
            ?->variants ?? collect();
    }

    public function addOptionFormFeature(): void
    {
        $this->optionForm['features'][] = [
            'id' => '',
            'value' => '',
            'description' => '',
        ];
    }

    public function removeOptionFormFeature(int $index): void
    {
        unset($this->optionForm['features'][$index]);
        $this->optionForm['features'] = array_values($this->optionForm['features']);
        $this->generateVariants();
    }

    public function saveOption(): void
    {
        $this->validate([
            'optionForm.option_id' => 'required|exists:options,id',
            'optionForm.features' => 'required|array|min:1',
            'optionForm.features.*.id' => 'required|exists:features,id',
        ]);

        Product::findOrFail($this->productId)->options()->attach(
            $this->optionForm['option_id'],
            ['features' => $this->optionForm['features']]
        );

        unset($this->attachedOptions, $this->productVariants);

        $this->resetOptionForm();
        $this->openModal = false;

        // Regenerar variantes automáticamente tras añadir opción
        $this->generateVariants();

        $this->dispatchSwal('success', '¡Éxito!', 'Opción agregada y variantes generadas correctamente.');
    }

    public function optionFormFeatureChange(int $index): void
    {
        $feature = Feature::find($this->optionForm['features'][$index]['id']);

        if ($feature) {
            $this->optionForm['features'][$index]['value'] = $feature->value;
            $this->optionForm['features'][$index]['description'] = $feature->description;
        }
    }

    public function deleteVariant(int $variantId): void
    {
        $variant = Variant::where('product_id', $this->productId)->findOrFail($variantId);

        if ($variant->image_path) {
            Storage::disk('public')->delete($variant->image_path);
        }

        $variant->features()->detach();
        $variant->delete();

        unset($this->productVariants);

        $this->dispatchSwal('success', '¡Eliminada!', 'La variante se eliminó correctamente.');
    }

    public function detachOption(int $optionId): void
    {
        Product::findOrFail($this->productId)->options()->detach($optionId);

        unset($this->attachedOptions, $this->productVariants);

        // Regenerar variantes tras quitar la opción del producto
        $this->generateVariants();

        $this->dispatchSwal('success', '¡Eliminada!', 'La opción se desvinculó del producto correctamente.');
    }

    public function removePivotFeature(int $optionId, int $featureIndex): void
    {
        $product = Product::findOrFail($this->productId);
        $option = $product->options()->where('options.id', $optionId)->first();

        if (! $option) {
            return;
        }

        $features = $option->pivot->features;

        if (count($features) <= 1) {
            $this->dispatchSwal('error', 'Acción inválida', 'La opción debe tener al menos un valor.');

            return;
        }

        unset($features[$featureIndex]);
        $features = array_values($features);

        $product->options()->updateExistingPivot($optionId, ['features' => $features]);

        unset($this->attachedOptions, $this->productVariants);

        // Regenerar variantes tras remover un atributo específico
        $this->generateVariants();

        $this->dispatchSwal('success', '¡Eliminado!', 'El valor se eliminó correctamente.');
    }

    private function resetOptionForm(): void
    {
        $this->optionForm = [
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
        return view('livewire.admin.products.variants');
    }
}
