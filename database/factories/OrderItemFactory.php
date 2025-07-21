<?php

namespace Database\Factories;

use App\Models\Orderitem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = Orderitem::class;

    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 5, 300),
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
