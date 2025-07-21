<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shopping_Cart;
use App\Models\User;
use App\Models\Product;

/**
 * @extends Factory<\App\Models\ShoppingCart>
 */
class Shopping_CartFactory extends Factory
{
    protected $model = Shopping_Cart::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'creation_date' => $this->faker->dateTime(),

        ];
    }
}
