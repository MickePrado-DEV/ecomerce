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

        $this->syncVariantsIfNeeded();
    }

    #[Computed]
    public function product(): Product
    {
        return Product::findOrFail($this->productId);
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

    #[Computed]
    public function expectedVariantCount(): int
    {
        $product = Product::with('options')->find($this->productId);

        if (! $product) {
            return 0;
        }

        return $this->calculateExpectedVariantCount($product);
    }

    private function calculateExpectedVariantCount(Product $product): int
    {
        if ($product->options->isEmpty()) {
            return 0;
        }

        $product->loadMissing('options');

        $counts = $this->normalizedFeatureGroups($product->options)
            ->map(fn (array $group) => count($group))
            ->filter(fn (int $count) => $count > 0);

        if ($counts->isEmpty()) {
            return 0;
        }

        return (int) $counts->reduce(fn (int $carry, int $count) => $carry * $count, 1);
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
    }

    public function saveOption(): void
    {
        $this->validate([
            'optionForm.option_id' => 'required|exists:options,id',
            'optionForm.features' => 'required|array|min:1',
            'optionForm.features.*.id' => 'required|exists:features,id',
        ]);

        $product = Product::findOrFail($this->productId);

        if ($product->options()->where('options.id', $this->optionForm['option_id'])->exists()) {
            $this->dispatchSwal('error', 'Opción duplicada', 'Esta opción ya está asignada al producto.');

            return;
        }

        $product->options()->attach(
            $this->optionForm['option_id'],
            ['features' => $this->normalizeFeatureRows($this->optionForm['features'])]
        );

        $this->resetOptionForm();
        $this->openModal = false;
        $this->generateVariants();

        $this->dispatchSwal(
            'success',
            '¡Éxito!',
            "Opción guardada. Se generaron {$this->expectedVariantCount} variantes."
        );
    }

    public function optionFormFeatureChange(int $index): void
    {
        $feature = Feature::find($this->optionForm['features'][$index]['id']);

        if ($feature) {
            $this->optionForm['features'][$index]['value'] = $feature->value;
            $this->optionForm['features'][$index]['description'] = $feature->description;
        }
    }

    public function detachOption(int $optionId): void
    {
        Product::findOrFail($this->productId)->options()->detach($optionId);

        $this->generateVariants();

        $this->dispatchSwal('success', '¡Eliminada!', 'La opción se desvinculó y las variantes se actualizaron.');
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

        $product->options()->updateExistingPivot($optionId, [
            'features' => $this->normalizeFeatureRows($features),
        ]);

        $this->generateVariants();

        $this->dispatchSwal('success', '¡Eliminado!', 'El valor se eliminó y las variantes se actualizaron.');
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

        $this->notifyProductStockVisibilityChanged();
        $this->dispatchSwal('success', '¡Eliminada!', 'La variante se eliminó correctamente.');
    }

    public function generateVariants(): void
    {
        $product = Product::with('options')->findOrFail($this->productId);

        $this->deleteAllProductVariants();

        if ($product->options->isEmpty()) {
            $this->refreshVariantState();
            $this->notifyProductStockVisibilityChanged();

            return;
        }

        $featureGroups = $this->normalizedFeatureGroups($product->options)->values()->all();

        if ($featureGroups === [] || collect($featureGroups)->contains(fn (array $group) => $group === [])) {
            $this->refreshVariantState();
            $this->notifyProductStockVisibilityChanged();

            return;
        }

        $combinations = $this->generateCombinations($featureGroups);

        foreach ($combinations as $combination) {
            $featureIds = collect($combination)
                ->pluck('id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            if ($featureIds === []) {
                continue;
            }

            $features = Feature::whereIn('id', $featureIds)->get();

            if ($features->count() !== count($featureIds)) {
                continue;
            }

            $variant = Variant::create([
                'product_id' => $this->productId,
                'sku' => $this->buildVariantSku($product->sku, $features),
                'image_path' => '',
                'stock' => 0,
            ]);

            foreach ($features as $feature) {
                $variant->features()->attach($feature->id, [
                    'option_id' => $feature->option_id,
                ]);
            }
        }

        $this->refreshVariantState();
        $this->notifyProductStockVisibilityChanged();
    }

    private function syncVariantsIfNeeded(): void
    {
        $product = Product::with(['options', 'variants'])->find($this->productId);

        if (! $product || $product->options->isEmpty()) {
            return;
        }

        $expected = $this->calculateExpectedVariantCount($product);

        if ($expected > 0 && $product->variants->count() !== $expected) {
            $this->generateVariants();
        }
    }

    /**
     * @param  Collection<int, Option>  $options
     * @return Collection<int, array<int, array<string, mixed>>>
     */
    private function normalizedFeatureGroups(Collection $options): Collection
    {
        return $options->map(
            fn (Option $option) => $this->normalizeFeatureRows($option->pivot->features ?? [])
        )->filter(fn (array $group) => $group !== []);
    }

    /**
     * @param  array<int, array<string, mixed>>  $features
     * @return array<int, array<string, mixed>>
     */
    private function normalizeFeatureRows(array $features): array
    {
        return collect($features)
            ->map(function (array $feature) {
                $id = $feature['id'] ?? $feature['feature_id'] ?? null;

                return [
                    'id' => $id ? (int) $id : null,
                    'value' => $feature['value'] ?? '',
                    'description' => $feature['description'] ?? '',
                ];
            })
            ->filter(fn (array $feature) => ! empty($feature['id']))
            ->values()
            ->all();
    }

    private function refreshVariantState(): void
    {
        unset($this->attachedOptions, $this->productVariants, $this->expectedVariantCount);
    }

    private function notifyProductStockVisibilityChanged(): void
    {
        $this->dispatch('product-stock-visibility-changed');
    }

    /**
     * @param  array<int, array<int, array<string, mixed>>>  $arrayData
     * @param  array<int, array<string, mixed>>  $combination
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function generateCombinations(array $arrayData, int $index = 0, array $combination = []): array
    {
        if ($index === count($arrayData)) {
            return [$combination];
        }

        $result = [];

        foreach ($arrayData[$index] as $item) {
            $tempCombination = $combination;
            $tempCombination[] = $item;

            $result = array_merge(
                $result,
                $this->generateCombinations($arrayData, $index + 1, $tempCombination)
            );
        }

        return $result;
    }

    private function buildVariantSku(string $productSku, Collection $features): string
    {
        $suffix = $features
            ->map(fn (Feature $feature) => strtoupper(substr(preg_replace('/\s+/', '-', $feature->value), 0, 12)))
            ->implode('-');

        $sku = "{$productSku}-{$suffix}";
        $base = $sku;
        $counter = 1;

        while (Variant::where('sku', $sku)->exists()) {
            $sku = "{$base}-{$counter}";
            $counter++;
        }

        return $sku;
    }

    private function deleteAllProductVariants(): void
    {
        $variants = Variant::where('product_id', $this->productId)->get();

        foreach ($variants as $variant) {
            if ($variant->image_path) {
                Storage::disk('public')->delete($variant->image_path);
            }

            $variant->features()->detach();
            $variant->delete();
        }
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
