<?php

namespace Database\Seeders;

use App\Models\CategoryItem;
use Illuminate\Database\Seeder;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i <= 10; $i++) {
            CategoryItem::factory()->create([
                'category_code' => fake()->countryCode(),
                'category_name' => fake()->name(),
            ]);
        }


    }
}
