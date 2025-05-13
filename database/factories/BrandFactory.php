<?php

// database/factories/BrandFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words($this->faker->numberBetween(1, 3), true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'logo' => 'brands/' . $this->faker->image('public/storage/brands', 400, 300, 'business', false),
        ];
    }
}
