<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Models\PaymentMethod;


class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['Credit Card', 'Debit Card', 'PayPal', 'PSE']),
            'description' => $this->faker->sentence(),
            'expiration_date' => $this->faker->creditCardExpirationDate(),
            'payment_id' => Payment::factory(),

        ];
    }
}
