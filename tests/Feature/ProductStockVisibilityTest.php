<?php

namespace Tests\Feature;

use App\Livewire\Admin\Products\ProductCreate;
use App\Livewire\Admin\Products\Variants;
use App\Models\Category;
use App\Models\Family;
use App\Models\Feature;
use App\Models\Option;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductStockVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_field_hides_when_option_is_added_without_page_reload(): void
    {
        $product = $this->createProduct();
        $option = Option::create(['name' => 'Color', 'type' => 1]);
        $red = Feature::create(['option_id' => $option->id, 'value' => 'rojo', 'description' => 'Rojo']);

        Livewire::test(ProductCreate::class, ['productModel' => $product])
            ->assertSet('hideProductStock', false)
            ->assertSet('showStockField', true);

        Livewire::test(Variants::class, ['productModel' => $product])
            ->set('optionForm.option_id', (string) $option->id)
            ->set('optionForm.features', [
                ['id' => (string) $red->id, 'value' => $red->value, 'description' => $red->description],
            ])
            ->call('saveOption')
            ->assertDispatched('product-stock-visibility-changed');

        Livewire::test(ProductCreate::class, ['productModel' => $product])
            ->call('refreshProductStockVisibility')
            ->assertSet('hideProductStock', true)
            ->assertSet('showStockField', false);
    }

    public function test_stock_field_returns_when_all_options_are_removed(): void
    {
        $product = $this->createProduct();
        $option = Option::create(['name' => 'Color', 'type' => 1]);
        $red = Feature::create(['option_id' => $option->id, 'value' => 'rojo', 'description' => 'Rojo']);

        $product->options()->attach($option->id, [
            'features' => [
                ['id' => $red->id, 'value' => $red->value, 'description' => $red->description],
            ],
        ]);

        Livewire::test(ProductCreate::class, ['productModel' => $product])
            ->assertSet('hideProductStock', true);

        Livewire::test(Variants::class, ['productModel' => $product])
            ->call('detachOption', $option->id)
            ->assertDispatched('product-stock-visibility-changed');

        Livewire::test(ProductCreate::class, ['productModel' => $product])
            ->call('refreshProductStockVisibility')
            ->assertSet('hideProductStock', false)
            ->assertSet('showStockField', true);
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
            'stock' => 25,
            'sub_category_id' => $subCategory->id,
        ]);
    }
}
