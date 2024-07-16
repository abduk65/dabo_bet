<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productNames = [["bale7", "Bread", "7"], ["bale10", "Bread", "10"], ["Donut", "Cake", "30"], ["Kuribat", "Cake", "40"]];
        foreach ($productNames as $productName) {
            Product::create([
                "name" => $productName[0],
                "type" => $productName[1],
                "unit_price" => $productName[2],
            ]);
        }
    }
}
