<?php

// database/factories/OrderFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'total' => $this->faker->numberBetween(1000, 50000),
            'subtotal' => $this->faker->numberBetween(1000, 50000),
            'tax' => $this->faker->numberBetween(100, 5000),
            'shipping_cost' => $this->faker->numberBetween(0, 2000),
            'discount' => $this->faker->numberBetween(0, 5000),
            'payment_status' => $this->faker->randomElement(['paid', 'pending', 'failed']),
            'shipping_status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'shipping_method_id' => \App\Models\ShippingMethod::factory(),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'shipping_address' => $this->faker->address(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
