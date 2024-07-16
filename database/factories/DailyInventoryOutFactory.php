<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyInventoryOut>
 */
class DailyInventoryOutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "quantity" => $this->faker->numberBetween(10, 20),
            "inventory_item_id" => $this->faker->randomElement(\App\Models\InventoryItem::all()->pluck('id')),
            "user_id" => $this->faker->randomElement(
                \App\Models\User::all()->pluck(
                    'id'
                )
            ),
            'unit_id' => $this->faker->randomElement(\App\Models\Unit::all()->pluck('id')),
        ];
    }
}
