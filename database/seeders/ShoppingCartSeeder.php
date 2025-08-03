<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShoppingCart;

class ShoppingCartSeeder extends Seeder
{
    public function run(): void
    {
        ShoppingCart::factory()->count(10)->create();
    }
}
