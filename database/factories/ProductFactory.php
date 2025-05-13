<?php

// database/factories/ProductFactory.php

namespace Database\Factories;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words($this->faker->numberBetween(1, 3), true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(1000, 100000),
            'sale_price' => $this->faker->optional(30)->numberBetween(500, 90000),
            'cost' => $this->faker->numberBetween(500, 50000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'brand_id' => \App\Models\Brand::factory(),
            'category_id' => \App\Models\Category::factory(),
            'status' => $this->faker->randomElement(['in_stock', 'out_of_stock', 'discontinued']),
            'featured' => $this->faker->boolean(20),
            'thumbnail' => 'products/' . $this->faker->image('public/storage/products', 800, 600, 'technics', false),
        ];
    }
}
