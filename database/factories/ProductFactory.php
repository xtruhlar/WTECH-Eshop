<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    protected $table = "products";

    public function definition()
    {
        $title = $this->faker->text(48);
        return [
            'id' => Str::uuid(),
            'title' => $title,
            'featuredImage' => getRandomImageUrl(),
            'slug' => Str::slug($title),
            'shortDescription' => $this->faker->text(200),
            'longDescription' => $this->faker->text(2000),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'availability' => $this->faker->randomElement(['IN_STOCK', 'IN_SHOP', 'OUT_OF_STOCK']),
        ];
    }
}
