<?php

// database/factories/PaymentFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'transaction_id' => $this->faker->uuid(),
            'payment_method' => $this->faker->randomElement(['card', 'paypal', 'cash']),
            'amount' => $this->faker->numberBetween(1000, 50000),
            'status' => $this->faker->randomElement(['successful', 'pending', 'failed']),
        ];
    }
}
