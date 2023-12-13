<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $unit = Unit::create(['name' => 'GR']);

        Item::insert(
            [
                ['name' => fake()->name(), 'id' => fake()->uuid(), 'code' => fake()->currencyCode(), 'units_id' => $unit->id, 'categ'],
                ['name' => 'Daging Ayam 2kg', 'id' => fake()->uuid()],
                ['name' => 'Daging Ayam 3kg', 'id' => fake()->uuid()],
            ]
        );
    }
}
