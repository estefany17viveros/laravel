<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'types' => $this->faker->randomElement(['Credit Card', 'Debit Card', 'PayPal', 'PSE']),
            'details' => $this->faker->creditCardNumber(),
            'expiration_date' => $this->faker->creditCardExpirationDate(),
            'CCV' => $this->faker->numberBetween(100, 999),
        ];
    }
}
