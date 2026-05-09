<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Miguel Angel Prado Garcia',
            'email' => 'mickeprd@gmail.com',
            'password' => bcrypt('pragmant64.'),
        ]);

        Storage::deleteDirectory('products');
        Storage::makeDirectory('products');



        $this->call([
            FamilySeeder::class,
            OptionSeeder::class
        ]);
        Product::factory(50)->create();
    }
}
