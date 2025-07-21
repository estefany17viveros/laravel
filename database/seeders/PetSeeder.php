<?php
// database/seeders/PetSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        Pet::factory()->count(10)->create();
    }
}
