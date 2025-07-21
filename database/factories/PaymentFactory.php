<?php
// database/factories/PaymentFactory.php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(10000, 500000),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'order_id' => Order::inRandomOrder()->first()?->id,
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()?->id,
        ];
    }
}
