<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->optional()->paragraph(),
            'price' => $this->faker->randomFloat(2, 5, 500), // Ej: entre 5.00 y 500.00
            'image' => $this->faker->optional()->imageUrl(),
            'category_id' => Category::factory(),
        ];
    }
}
