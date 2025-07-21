<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Orderitem;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        Orderitem::factory()->count(20)->create();
    }
}
