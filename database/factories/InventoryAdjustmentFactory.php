<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryAdjustment>
 */
class InventoryAdjustmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $inventory = $this->faker->randomElement(InventoryItem::all()->pluck('id'));
        return [
            'inventory_item_id' => $inventory,
            'quantity' => $this->faker->numberBetween(10, 50),
            'unit_id' => InventoryItem::find($inventory)->unit_id,
            'remark' => $this->faker->sentence()
        ];
    }
}
