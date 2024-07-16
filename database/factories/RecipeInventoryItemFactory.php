<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeInventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "recipe_id" => $this->faker->randomElement(Recipe::all()->pluck("id")),
            "inventory_item_id" => $this->faker->randomElement(InventoryItem::all()->pluck("id")),
            "quantity" => $this->faker->numberBetween(1, 150),
            "unit_id" => $this->faker->randomElement(Unit::all()->pluck("id")),
        ];
    }
}
