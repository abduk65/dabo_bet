<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'item_name' => $this->faker->unique()->company . ' ' . $this->faker->word, // Combine company name and a random word for a unique item name
        //     'unit' => $this->faker->randomElement(['kg', 'g', 'm', 'cm', 'pcs']), // Random unit of measurement
        //     'quantity' => $this->faker->numberBetween(1, 1000), // Quantity between 1 and 1000
        //     'price' => $this->faker->randomFloat(2, 0.01, 999.99), // Price between 0.01 and 999.99 with 2 decimals
        //     'total_price' => function ($inventoryitem) {  // Closure to calculate total price dynamically
        //         return $inventoryitem['price'] * $inventoryitem['quantity'];
        //     },
        // ];
        $quantity = $this->faker->numberBetween(20, 5000);
        $price = $this->faker->numberBetween(150, 2500);
        return [
            "item_name" => $this->faker->unique()->word,
            "brand_id" => $this->faker->randomElement(\App\Models\Brand::all()->pluck('id')),
            "unit_id" => $this->faker->randomElement(\App\Models\Unit::all()->pluck('id')),
            "quantity" => $quantity,
            "price" => $price,
            "batch_number" => $this->faker->numberBetween(1, 50),
            "total_price" => (int) $quantity * (int) $price,
            "user_id" => $this->faker->randomElement(\App\Models\User::all()->pluck('id'))
        ];
    }
}
