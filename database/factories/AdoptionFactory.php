<?php
// database/factories/AdoptionFactory.php

namespace Database\Factories;

use App\Models\Adoption;
use App\Models\Pet;
use App\Models\Shelter;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdoptionFactory extends Factory
{
    protected $model = Adoption::class;

    public function definition(): array
    {
        return [
            'application_date' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'comments' => $this->faker->sentence(),
            'pet_id' => Pet::inRandomOrder()->first()?->id,
            'shelter_id' => Shelter::inRandomOrder()->first()?->id,
        ];
    }
}
