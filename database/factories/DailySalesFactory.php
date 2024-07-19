<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Commission;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailySales>
 */
class DailySalesFactory extends Factory
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
            'product_id' => $this->faker->randomElement(Product::all()->pluck('id')->toArray()),
            'quantity' => $this->faker->numberBetween(50, 5000),
            'adari' => $this->faker->numberBetween(0, 40),
            'branch_id' => $this->faker->randomElement(Branch::all()->pluck('id')->toArray()),
            'commission_id' => $this->faker->randomElement(Commission::all()->pluck('id')),
            'commission_quantity' => $this->faker->numberBetween(0, 50),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
