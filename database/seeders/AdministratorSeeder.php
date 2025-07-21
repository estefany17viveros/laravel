<?php
// database/seeders/AdministratorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrator;

class AdministratorSeeder extends Seeder
{
    public function run(): void
    {
        Administrator::factory()->count(5)->create();
    }
}
