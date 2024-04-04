<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => fake()->uuid,
            'code' => 'KTG01',
            'name' => 'Bahan mentah'
        ]);

        DB::table('categories')->insert([
            'id' => fake()->uuid,
            'code' => 'KTG02',
            'name' => 'Bahan setengah jadi'
        ]);
    }
}
