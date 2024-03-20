<?php

namespace Database\Factories;

use App\Models\CentralKitchen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CentralKitchen>
 */
class CentralKitchenFactory extends Factory
{


    protected $model = CentralKitchen::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid,
            'code' => fake()->countryCode(),
            'address' => fake()->address,
            'phone' => fake()->phoneNumber,
            'email' => fake()->email
        ];
    }
}
