<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => "Recipe " . $this->faker->randomLetter(),
            "product_id" => $this->faker->randomElement(Product::all()->pluck("id")),
            "instruction" => $this->faker->text(250)
        ];
    }
}
