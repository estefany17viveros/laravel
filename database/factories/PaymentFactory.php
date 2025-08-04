<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use App\Models\User;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $order = \App\Models\Order::inRandomOrder()->first() ?? \App\Models\Order::factory()->create();
        $user = \App\Models\User::inRandomOrder()->first() ?? \App\Models\User::factory()->create();

        return [
            'amount' => $this->faker->numberBetween(10000, 500000),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),

            'payable_id' => $order->id,
            'payable_type' => \App\Models\Order::class,

            // ✅ Usuario que hizo el pago
            'user_id' => $user->id,

            // ✅ Método de pago
            'payment_method_id' => \App\Models\PaymentMethod::inRandomOrder()->first()?->id
                                   ?? \App\Models\PaymentMethod::factory()->create()->id,
        ];
    }
}
