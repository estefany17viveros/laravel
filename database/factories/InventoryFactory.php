<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'quantity_available' => $this->faker->numberBetween(0, 100),
            'product_id' => Product::factory(),
        ];
    }
}
