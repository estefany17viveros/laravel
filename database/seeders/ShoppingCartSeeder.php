<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shopping_cart;

class ShoppingCartSeeder extends Seeder
{
    public function run(): void
    {
        Shopping_cart::factory()->count(10)->create();
    }
}
