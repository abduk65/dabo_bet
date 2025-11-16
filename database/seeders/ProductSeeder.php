<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kgUnit = Unit::where('unit_abbreviation', 'kg')->first();
        $piecesUnit = Unit::where('unit_abbreviation', 'pcs')->first();

        $products = [
            // Bread products
            [
                'name' => 'White Bread 400g',
                'name_am' => 'ነጭ ዳቦ 400 ግራም',
                'type' => 'Bread',
                'description' => 'Standard white sandwich bread, 400g loaf',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'White Bread 800g',
                'name_am' => 'ነጭ ዳቦ 800 ግራም',
                'type' => 'Bread',
                'description' => 'Large white sandwich bread, 800g loaf',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Whole Wheat Bread 400g',
                'name_am' => 'የስንዴ ዳቦ 400 ግራም',
                'type' => 'Bread',
                'description' => 'Whole wheat bread, 400g loaf',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Hamburger Bun',
                'name_am' => 'ሃምበርገር ዳቦ',
                'type' => 'Bread',
                'description' => 'Sesame hamburger buns, pack of 6',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Hot Dog Bun',
                'name_am' => 'ሆት ዶግ ዳቦ',
                'type' => 'Bread',
                'description' => 'Hot dog buns, pack of 6',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'French Baguette',
                'name_am' => 'ፈረንሳይ ዳቦ',
                'type' => 'Bread',
                'description' => 'Traditional French baguette',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 1,
                'is_active' => true,
            ],

            // Cake products
            [
                'name' => 'Vanilla Sponge Cake 1kg',
                'name_am' => 'ቫኒላ ኬክ 1 ኪሎ',
                'type' => 'Cake',
                'description' => 'Classic vanilla sponge cake, 1kg',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Chocolate Cake 1kg',
                'name_am' => 'ቾኮሌት ኬክ 1 ኪሎ',
                'type' => 'Cake',
                'description' => 'Rich chocolate cake, 1kg',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Birthday Cake 2kg',
                'name_am' => 'የልደት ኬክ 2 ኪሎ',
                'type' => 'Cake',
                'description' => 'Custom birthday cake with decorations, 2kg',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Cupcakes Pack of 6',
                'name_am' => 'ካፕኬክ 6 ፓክ',
                'type' => 'Cake',
                'description' => 'Assorted cupcakes, pack of 6',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Muffins Pack of 4',
                'name_am' => 'ሙፊን 4 ፓክ',
                'type' => 'Cake',
                'description' => 'Blueberry muffins, pack of 4',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 3,
                'is_active' => true,
            ],

            // Other products
            [
                'name' => 'Pizza Dough Ball 500g',
                'name_am' => 'የፒዛ ዳቦ 500 ግራም',
                'type' => 'Others',
                'description' => 'Fresh pizza dough ball, 500g',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pastry Sheets Pack of 10',
                'name_am' => 'ፓስትሪ ሺት 10 ፓክ',
                'type' => 'Others',
                'description' => 'Puff pastry sheets, pack of 10',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Croissant Pack of 4',
                'name_am' => 'ክሮሳንት 4 ፓክ',
                'type' => 'Others',
                'description' => 'Butter croissants, pack of 4',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Doughnut Pack of 6',
                'name_am' => 'ዶናት 6 ፓክ',
                'type' => 'Others',
                'description' => 'Glazed doughnuts, pack of 6',
                'unit_id' => $piecesUnit->id,
                'shelf_life_days' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
    }
}
