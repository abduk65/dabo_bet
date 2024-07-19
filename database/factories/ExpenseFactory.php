<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(1, 50, 15000),
            'user_id' => $this->faker->randomElement(User::where('role', 'admin')->orWhere('role', 'store_keeper')->pluck('id')->toArray()),
            'branch_id' => $this->faker->randomElement(Branch::all()->pluck('id')->toArray()),
            'type' => 'expected'
        ];
    }
}
