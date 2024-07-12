<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // You can create a single product
        Product::create([
            'id' => Str::uuid(),
            'featuredImage' => 'path/to/image.jpg',
            'title' => 'Sample Product',
            'shortDescription' => 'This is a short description of the product.',
            'longDescription' => 'This is a longer description of the product, detailing features and benefits.',
            'price' => 1999,
            // Add any other fields you need
        ]);

        // Or create multiple products using a factory if you have one defined
        Product::factory()->count(50)->create();
    }
}
