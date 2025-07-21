<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Answer;

class AnswerSeeder extends Seeder
{
    public function run(): void
    {
        Answer::factory()->count(10)->create();
    }
}
