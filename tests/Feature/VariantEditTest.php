<?php

namespace Tests\Feature;

use App\Livewire\Admin\Products\VariantEdit;
use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class VariantEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_variant_can_be_updated_with_sku_and_stock(): void
    {
        Storage::fake('public');

        $product = $this->createProduct();
        $variant = Variant::create([
            'product_id' => $product->id,
            'sku' => 'PROD-001-ROJO',
            'image_path' => '',
            'stock' => 5,
        ]);

        Livewire::test(VariantEdit::class, ['product' => $product, 'variant' => $variant])
            ->set('sku', 'PROD-001-ROJO-XL')
            ->set('stock', 12)
            ->call('save')
            ->assertRedirect(route('admin.products.edit', $product));

        $variant->refresh();

        $this->assertSame('PROD-001-ROJO-XL', $variant->sku);
        $this->assertSame(12, $variant->stock);
    }

    public function test_variant_image_can_be_replaced(): void
    {
        Storage::fake('public');

        $product = $this->createProduct();
        $variant = Variant::create([
            'product_id' => $product->id,
            'sku' => 'PROD-001-ROJO',
            'image_path' => '',
            'stock' => 0,
        ]);

        Livewire::test(VariantEdit::class, ['product' => $product, 'variant' => $variant])
            ->set('image', UploadedFile::fake()->image('variant.jpg'))
            ->call('save')
            ->assertRedirect(route('admin.products.edit', $product));

        $variant->refresh();

        $this->assertNotEmpty($variant->image_path);
        Storage::disk('public')->assertExists($variant->image_path);
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
