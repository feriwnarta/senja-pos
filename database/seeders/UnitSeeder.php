<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            'id' => fake()->uuid,
            'code' => 'UNIT01',
            'name' => 'Gram'
        ]);

        DB::table('units')->insert([
            'id' => fake()->uuid,
            'code' => 'UNIT02',
            'name' => 'Pcs'
        ]);
    }
}
