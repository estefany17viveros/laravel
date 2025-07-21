<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'shipping_address' => $this->faker->address(),
            'cost' => $this->faker->randomFloat(2, 5, 100),
            'status' => $this->faker->randomElement(['pending', 'shipped', 'delivered', 'cancelled']),
            'shipping_method' => $this->faker->randomElement(['Standard', 'Express', 'Overnight']),
            'order_id' => Order::factory(),
        ];
    }
}
