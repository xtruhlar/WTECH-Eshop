<?php

namespace Database\Factories;

use App\Models\GalleryImage;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class GalleryImageFactory extends Factory
{
    protected $model = GalleryImage::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'imageURL' => getRandomImageUrl(),
            'productId' => Product::factory(),  // Assuming you have a ProductFactory
        ];
    }
}
