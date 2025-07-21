<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(),
            'creation_date' => $this->faker->date(),
            'topic_id' => Topic::factory(),  // se crea un topic si no existe
            'users_id' => User::factory(),   // se crea un usuario si no existe
        ];
    }
}
