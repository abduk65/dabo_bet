<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ["Adama", 3],
            ["Choco Glaze", 5],
            ["Saha", 3],
            ["Gazelle", 4],

        ];
        foreach ($brands as $brand) {
            Brand::create(["name" => $brand[0], "product_type_id" => $brand[1]]);
        }
    }
}
