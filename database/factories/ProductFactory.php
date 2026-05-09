<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [

            'sku' => $this->faker->unique()->bothify('SKU-####'),

            'name' => ucfirst($this->faker->words(3, true)),

            'description' => $this->faker->paragraph(),

            'image_path' => 'products/' . $this->faker->image(
                storage_path('app/public/products'),
                640,
                480,
                null,
                false
            ),

            'price' => $this->faker->randomFloat(2, 10, 5000),

            'sub_category_id' => SubCategory::query()->inRandomOrder()->value('id')
                ?? SubCategory::factory(),
        ];
    }
}
