<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\GalleryImage;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

include app_path('Helpers/randomImage.php');

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create some categories
        $categories = Category::factory()->count(5)->create();
        $manufacturers = Manufacturer::factory()->count(5)->create();

        // Create a guest user
        User::factory()->create([
            'id' => 9223372036854775807,
            'name' => 'Guest User',
            'email' => 'guest@example.com',
        ]);

        // Create an admin user
        User::factory()->create([
            'id' => 9223372036854775806,
            'name' => 'Admin User',
            'email' => 'admin@stuba.sk',
            'role' => 'admin',
            'password' => bcrypt('123456789'),
        ]);

        DB::table('carts')->insert([
            'id' => Str::uuid(),
            'user_id' => 9223372036854775807,
        ]);

        DB::table('carts')->insert([
            'id' => Str::uuid(),
            'user_id' => 9223372036854775806,
        ]);

        // Create products and assign each to a random category
        Product::factory()->count(30)->create()->each(function ($product) use ($categories, $manufacturers) {
            // Randomly pick a category and assign it to the product
            $category = $categories->random();
            $manufacturer = $manufacturers->random();
            $product->categoryId = $category->id;
            $product->manufacturerId = $manufacturer->id;
            $product->save();

            GalleryImage::factory()->count(5)->create([
                'productId' => $product->id,
            ]);
        });
    }
}
