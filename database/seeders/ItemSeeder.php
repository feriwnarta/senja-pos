<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::insert(
            [
                ['name' => 'Daging Ayam 1kg', 'id' => fake()->uuid()],
                ['name' => 'Daging Ayam 2kg', 'id' => fake()->uuid()],
                ['name' => 'Daging Ayam 3kg', 'id' => fake()->uuid()],
            ]
        );
    }
}
