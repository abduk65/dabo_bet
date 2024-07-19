<?php

namespace Database\Factories;

use App\Models\CommissionRecipient;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commission>
 */
class CommissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->randomElement(Product::all('id')),
            'discount_amount' => $this->faker->randomFloat(2, 0, 1),
            'commission_recipient_id' => $this->faker->randomElement(CommissionRecipient::all()->pluck('id')),
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
