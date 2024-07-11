<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = [
            "flour", "yeast", "festal", "majmex", "cup", "cookies_small", "cookies_big", "wrap", "supergato", "chocolate", "sugar", "salt", "egg"
        ];

        foreach($product as $one){
            ProductType::create(
                ['name' => $one]
            );
        }
    }
}
