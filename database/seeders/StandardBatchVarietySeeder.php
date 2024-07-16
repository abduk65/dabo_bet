<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\StandardBatchVariety;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Arr;

class StandardBatchVarietySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StandardBatchVariety::create([
            'recipe_id' => Arr::random(Recipe::all()->pluck('id')->toArray()),
            'single_factor_expected_output' => rand(2000, 2500),
        ]);
    }
}
