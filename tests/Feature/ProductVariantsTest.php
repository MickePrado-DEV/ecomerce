<?php

namespace Tests\Feature;

use App\Livewire\Admin\Products\Variants;
use App\Models\Category;
use App\Models\Family;
use App\Models\Feature;
use App\Models\Option;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductVariantsTest extends TestCase
{
    use RefreshDatabase;

    public function test_saving_option_generates_variants_from_combinations(): void
    {
        $product = $this->createProduct();
        $colorOption = Option::create(['name' => 'Color', 'type' => 1]);
        $sizeOption = Option::create(['name' => 'Talla', 'type' => 1]);

        $red = Feature::create(['option_id' => $colorOption->id, 'value' => 'rojo', 'description' => 'Rojo']);
        $blue = Feature::create(['option_id' => $colorOption->id, 'value' => 'azul', 'description' => 'Azul']);
        $small = Feature::create(['option_id' => $sizeOption->id, 'value' => 's', 'description' => 'S']);
        $large = Feature::create(['option_id' => $sizeOption->id, 'value' => 'l', 'description' => 'L']);

        Livewire::test(Variants::class, ['productModel' => $product])
            ->set('optionForm.option_id', (string) $colorOption->id)
            ->set('optionForm.features', [
                ['id' => (string) $red->id, 'value' => $red->value, 'description' => $red->description],
                ['id' => (string) $blue->id, 'value' => $blue->value, 'description' => $blue->description],
            ])
            ->call('saveOption')
            ->assertDispatched('swal');

        Livewire::test(Variants::class, ['productModel' => $product])
            ->set('optionForm.option_id', (string) $sizeOption->id)
            ->set('optionForm.features', [
                ['id' => (string) $small->id, 'value' => $small->value, 'description' => $small->description],
                ['id' => (string) $large->id, 'value' => $large->value, 'description' => $large->description],
            ])
            ->call('saveOption');

        $this->assertDatabaseCount('variants', 4);
        $this->assertEquals(4, Variant::where('product_id', $product->id)->count());

        $variant = Variant::where('product_id', $product->id)->first();
        $this->assertNotNull($variant);
        $this->assertNotEmpty($variant->sku);
        $this->assertCount(2, $variant->features);
    }

    public function test_single_option_with_four_colors_generates_four_variants(): void
    {
        $product = $this->createProduct();
        $colorOption = Option::create(['name' => 'Color', 'type' => 1]);

        $features = collect(['Rojo', 'Azul', 'Verde', 'Negro'])->map(fn (string $name) => Feature::create([
            'option_id' => $colorOption->id,
            'value' => strtolower($name),
            'description' => $name,
        ]));

        Livewire::test(Variants::class, ['productModel' => $product])
            ->set('optionForm.option_id', (string) $colorOption->id)
            ->set('optionForm.features', $features->map(fn (Feature $feature) => [
                'id' => (string) $feature->id,
                'value' => $feature->value,
                'description' => $feature->description,
            ])->all())
            ->call('saveOption');

        $this->assertDatabaseCount('variants', 4);
        $this->assertEquals(4, Variant::where('product_id', $product->id)->count());

        $variant = Variant::where('product_id', $product->id)->with('features')->first();
        $this->assertCount(1, $variant->features);
    }

    public function test_mount_regenerates_variants_when_options_exist_without_variants(): void
    {
        $product = $this->createProduct();
        $colorOption = Option::create(['name' => 'Color', 'type' => 1]);

        $features = collect(['Rojo', 'Azul', 'Verde', 'Negro'])->map(fn (string $name) => Feature::create([
            'option_id' => $colorOption->id,
            'value' => strtolower($name),
            'description' => $name,
        ]));

        $product->options()->attach($colorOption->id, [
            'features' => $features->map(fn (Feature $feature) => [
                'id' => $feature->id,
                'value' => $feature->value,
                'description' => $feature->description,
            ])->all(),
        ]);

        $this->assertDatabaseCount('variants', 0);

        Livewire::test(Variants::class, ['productModel' => $product]);

        $this->assertDatabaseCount('variants', 4);
    }

    public function test_generate_variants_button_creates_expected_count(): void
    {
        $product = $this->createProduct();
        $colorOption = Option::create(['name' => 'Color', 'type' => 1]);
        $red = Feature::create(['option_id' => $colorOption->id, 'value' => 'rojo', 'description' => 'Rojo']);
        $blue = Feature::create(['option_id' => $colorOption->id, 'value' => 'azul', 'description' => 'Azul']);

        $product->options()->attach($colorOption->id, [
            'features' => [
                ['id' => $red->id, 'value' => $red->value, 'description' => $red->description],
                ['id' => $blue->id, 'value' => $blue->value, 'description' => $blue->description],
            ],
        ]);

        Livewire::test(Variants::class, ['productModel' => $product])
            ->call('generateVariants')
            ->assertSet('productVariants', fn ($variants) => $variants->count() === 2);
    }

    public function test_delete_variant_removes_record(): void
    {
        $product = $this->createProduct();
        $variant = Variant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-SKU-001',
            'image_path' => '',
        ]);

        Livewire::test(Variants::class, ['productModel' => $product])
            ->call('deleteVariant', $variant->id)
            ->assertDispatched('swal');

        $this->assertDatabaseMissing('variants', ['id' => $variant->id]);
    }

    private function createProduct(): Product
    {
        $family = Family::create(['name' => 'Ropa']);
        $category = Category::create(['name' => 'Camisetas', 'family_id' => $family->id]);
        $subCategory = SubCategory::create(['name' => 'Basic', 'category_id' => $category->id]);

        return Product::create([
            'sku' => 'PROD-001',
            'name' => 'Camiseta',
            'description' => 'Descripción',
            'image_path' => '',
            'price' => 100,
            'sub_category_id' => $subCategory->id,
        ]);
    }
}
