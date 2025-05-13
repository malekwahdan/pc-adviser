<?php

// database/factories/ProductImageFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'image_path' => 'products/' . $this->faker->image('public/storage/products', 800, 600, 'technics', false),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
