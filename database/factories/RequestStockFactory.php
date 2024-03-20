<?php

namespace Database\Factories;

use App\Models\RequestStock;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RequestStock>
 */
class RequestStockFactory extends Factory
{
    protected $model = RequestStock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid,
            'warehouses_id' => Warehouse::factory()->make()->id,
            'code' => fake()->countryCode,
        ];
    }
}
