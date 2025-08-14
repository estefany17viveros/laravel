<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Payment_Type;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $paymentType = Payment_Type::inRandomOrder()->first() ?? Payment_Type::factory()->create();

        $payableModels = [
            Order::class => Order::inRandomOrder()->first() ?? Order::factory()->create(),
            Veterinarian::class => Veterinarian::inRandomOrder()->first() ?? Veterinarian::factory()->create(),
            Trainer::class => Trainer::inRandomOrder()->first() ?? Trainer::factory()->create(),
        ];

        $payableType = $this->faker->randomElement(array_keys($payableModels));
        $payable = $payableModels[$payableType];

        return [
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
    
            'user_id' => $user->id,
            'payment_types_id' => $paymentType->id,
            
            // Relación polimórfica
            'payable_id' => $payable->id,
            'payable_type' => $payableType,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Payment $payment) {
            // Lógica adicional después de crear el pago si es necesaria
        });
    }
}