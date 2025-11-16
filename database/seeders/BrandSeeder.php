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
                'brand_name' => 'Mama\'s Choice',
                'manufacturer' => 'Ethiopian Grain Trading Enterprise',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Local Ethiopian flour brand, most popular',
            ],
            [
                'brand_name' => 'Golden Harvest',
                'manufacturer' => 'Addis Milling PLC',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Premium Ethiopian wheat flour',
            ],
            [
                'brand_name' => 'Sheger',
                'manufacturer' => 'Sheger Flour Factory',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Mid-range Ethiopian flour brand',
            ],

            // International brands
            [
                'brand_name' => 'Lesaffre',
                'manufacturer' => 'Lesaffre Group',
                'country_of_origin' => 'France',
                'description' => 'International yeast and bread improver brand',
            ],
            [
                'brand_name' => 'AB Mauri',
                'manufacturer' => 'AB Mauri',
                'country_of_origin' => 'UK',
                'description' => 'Yeast and bakery ingredients',
            ],
            [
                'brand_name' => 'Puratos',
                'manufacturer' => 'Puratos Group',
                'country_of_origin' => 'Belgium',
                'description' => 'Premium bakery ingredients',
            ],

            // Oil brands
            [
                'brand_name' => 'Fresh',
                'manufacturer' => 'Fresh Plc',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Ethiopian vegetable oil brand',
            ],
            [
                'brand_name' => 'Eder',
                'manufacturer' => 'Eder Foods',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Ethiopian palm oil brand',
            ],

            // Sugar brands
            [
                'brand_name' => 'Wonji',
                'manufacturer' => 'Wonji Sugar Factory',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Ethiopian sugar producer',
            ],
            [
                'brand_name' => 'Metehara',
                'manufacturer' => 'Metehara Sugar Factory',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Ethiopian sugar brand',
            ],

            // Dairy brands
            [
                'brand_name' => 'Mama Dairy',
                'manufacturer' => 'Mama Dairy Farm',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Local dairy products',
            ],
            [
                'brand_name' => 'Holland Dairy',
                'manufacturer' => 'Holland Dairy Ethiopia',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Imported dairy products',
            ],

            // Generic/No brand
            [
                'brand_name' => 'Generic',
                'manufacturer' => 'Various',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Generic unbranded items',
            ],
            [
                'brand_name' => 'Local Market',
                'manufacturer' => 'Local Suppliers',
                'country_of_origin' => 'Ethiopia',
                'description' => 'Items from local market vendors',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('Brands seeded successfully!');
    }
}
