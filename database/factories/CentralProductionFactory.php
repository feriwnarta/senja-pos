<?php

namespace Database\Factories;

use App\Models\CentralKitchen;
use App\Models\CentralProduction;
use App\Models\RequestStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CentralProduction>
 */
class CentralProductionFactory extends Factory
{

    protected $model = CentralProduction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid,
            'request_stocks_id' => RequestStock::factory()->make()->id,
            'central_kitchens_id' => CentralKitchen::factory()->make()->id,
            'code' => fake()->countryCode,
            'increment' => 1,
        ];
    }
}
