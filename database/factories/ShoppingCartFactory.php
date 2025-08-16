<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShoppingCart;
use App\Models\User;

/**
 * @extends Factory<\App\Models\ShoppingCart>
 */
class ShoppingCartFactory extends Factory
{
    protected $model = ShoppingCart::class;

    public function definition(): array
    {
        return [
           
            'quantity' => $this->faker->numberBetween(1, 5),
            'creation_date' => $this->faker->dateTime(),
             'user_id' => User::factory(),

        ];
    }
}
