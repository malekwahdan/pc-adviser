<?php

// database/factories/ShippingMethodFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Standard', 'Express', 'Overnight', 'Free']),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(0, 2000),
            'estimated_delivery_time' => $this->faker->randomElement(['1-3 days', '3-5 days', 'Next day', '5-7 days']),
        ];
    }
}
