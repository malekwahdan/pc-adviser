<?php

// database/factories/CouponFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->bothify('????##')),
            'value' => $this->faker->numberBetween(5, 50),
            'min_order_amount' => $this->faker->optional()->numberBetween(1000, 5000),
            'max_discount_amount' => $this->faker->optional()->numberBetween(500, 2000),
            'usage_limit' => $this->faker->optional()->numberBetween(10, 100),
            'used_count' => 0,
            'start_date' => now(),
            'expiry_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
            'status' => true,
        ];
    }
}
