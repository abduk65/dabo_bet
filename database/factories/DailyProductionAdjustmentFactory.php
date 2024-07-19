<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyProductionAdjustment>
 */
class DailyProductionAdjustmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween("-6 months", "now");
        return [
            'type' => $this->faker->randomElement(['damaged', 'stale', 'worker_error']),
            'product_id' => $this->faker->randomElement(Product::all()->pluck('id')),
            'quantity' => $this->faker->numberBetween(50, 250),
            'unit_id' => 3,

            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
