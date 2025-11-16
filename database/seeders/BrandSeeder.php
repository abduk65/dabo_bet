<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Ethiopian brands
            [
                'brand_name' => 'Mama\'s Choice',                'country_of_origin' => 'Ethiopia',            ],
            [
                'brand_name' => 'Golden Harvest',                'country_of_origin' => 'Ethiopia',            ],
            [
                'brand_name' => 'Sheger',                'country_of_origin' => 'Ethiopia',            ],

            // International brands
            [
                'brand_name' => 'Lesaffre',                'country_of_origin' => 'France',            ],
            [
                'brand_name' => 'AB Mauri',                'country_of_origin' => 'UK',            ],
            [
                'brand_name' => 'Puratos',                'country_of_origin' => 'Belgium',            ],

            // Oil brands
            [
                'brand_name' => 'Fresh',                'country_of_origin' => 'Ethiopia',            ],
            [
                'brand_name' => 'Eder',                'country_of_origin' => 'Ethiopia',            ],

            // Sugar brands
            [
                'brand_name' => 'Wonji',                'country_of_origin' => 'Ethiopia',            ],
            [
                'brand_name' => 'Metehara',                'country_of_origin' => 'Ethiopia',            ],

            // Dairy brands
            [
                'brand_name' => 'Mama Dairy',                'country_of_origin' => 'Ethiopia',            ],
            [
                'brand_name' => 'Holland Dairy',                'country_of_origin' => 'Ethiopia',            ],

            // Generic/No brand
            [
                'brand_name' => 'Generic',                'country_of_origin' => 'Ethiopia',            ],
            [
                'brand_name' => 'Local Market',                'country_of_origin' => 'Ethiopia',            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('Brands seeded successfully!');
    }
}
