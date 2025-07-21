<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;
use App\Models\Trainer;
use App\Models\Veterinarian;
use App\Models\Requestt;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'duration' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'veterinarian_id' => Veterinarian::inRandomOrder()->first()?->id,
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
            'requestt_id' => Requestt::inRandomOrder()->first()?->id,
        ];
    }
}
