<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashCollected>
 */
class CashCollectedFactory extends Factory
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
            'branch_id' => $this->faker->randomElement(Branch::all()->pluck('id')->toArray()),
            'amount' => $this->faker->randomFloat(2, 0, 50000),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
