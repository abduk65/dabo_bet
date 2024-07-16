<?php

namespace Database\Factories;

use App\Models\StandardBatchVariety;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween("-1 year", "now");
        return [
            "output_count" => $this->faker->numberBetween(2000, 2500),
            "standard_batch_variety_id" => $this->faker->randomElement(StandardBatchVariety::all()->pluck("id")),
            "variety_factor" => $this->randomDecimal(0.1, 2, 1),
            "created_at" => $date,
            "updated_at" => $date
        ];
    }

    public function randomDecimal(float $min, float $max, int $digit = 2): float|int
    {
        return mt_rand($min * 10, $max * 10) / pow(10, $digit);
    }
}
