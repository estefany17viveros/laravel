<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        return [
            'types' => $this->faker->randomElement(['Credit Card', 'Debit Card', 'PayPal']),
            'details' => $this->faker->creditCardNumber(),
            'expiration_date' => $this->faker->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
            'CCV' => $this->faker->numberBetween(100, 999),
        ];
    }
}
