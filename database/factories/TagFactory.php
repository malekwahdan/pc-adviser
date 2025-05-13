<?php

// database/factories/TagFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words($this->faker->numberBetween(1, 3), true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
