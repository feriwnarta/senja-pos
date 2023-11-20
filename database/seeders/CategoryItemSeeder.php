<?php

namespace Database\Seeders;

use App\Models\Category;
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
            Category::factory()->create([
                'code' => fake()->countryCode(),
                'name' => fake()->name(),
            ]);
        }


    }
}
