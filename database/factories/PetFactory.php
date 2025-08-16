<?php

// database/factories/PetFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pet;
use App\Models\Trainer;
use App\Models\Shelter;
use App\Models\User;
use App\Models\Veterinary;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'age' => $this->faker->numberBetween(1, 15),
            'species' => $this->faker->randomElement(['Dog', 'Cat', 'Bird', 'Rabbit']),
            'breed' => $this->faker->word(),
            'size' => $this->faker->randomFloat(2, 1, 50),
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'description' => $this->faker->paragraph(),
            'photo' => $this->faker->imageUrl(640, 480, 'animals', true),
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
            'shelter_id' => Shelter::inRandomOrder()->first()?->id,
            'user_id' => User::inRandomOrder()->first()?->id,
            'veterinarian_id' => Veterinary::inRandomOrder()->first()?->id,
        ];
    }
}
