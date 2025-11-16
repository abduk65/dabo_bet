<?php

namespace Database\Seeders;

use App\Models\MaterialType;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kgUnit = Unit::where('unit_abbreviation', 'kg')->first();
        $lUnit = Unit::where('unit_abbreviation', 'L')->first();
        $piecesUnit = Unit::where('unit_abbreviation', 'pcs')->first();

        $materialTypes = [
            // Flour types
            [
                'type_code' => 'FLOUR-AP',
                'type_name' => 'All Purpose Flour',
                'type_name_am' => 'የሁሉም ዓላማ ዱቄት',
                'category' => 'flour',
                'default_unit_id' => $kgUnit->id,
                'description' => 'White all-purpose flour for bread and cake',
            ],
            [
                'type_code' => 'FLOUR-WW',
                'type_name' => 'Whole Wheat Flour',
                'type_name_am' => 'የስንዴ ዱቄት',
                'category' => 'flour',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Whole wheat flour for health bread',
            ],

            // Ersho (yeast)
            [
                'type_code' => 'YEAST-DRY',
                'type_name' => 'Dry Yeast',
                'type_name_am' => 'ደረቅ እርሾ',
                'category' => 'ersho',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Active dry yeast for bread making',
            ],
            [
                'type_code' => 'YEAST-INST',
                'type_name' => 'Instant Yeast',
                'type_name_am' => 'ፈጣን እርሾ',
                'category' => 'ersho',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Instant yeast for quick rise',
            ],

            // Bread improvers
            [
                'type_code' => 'IMPROVER',
                'type_name' => 'Bread Improver',
                'type_name_am' => 'የዳቦ መሻሻያ',
                'category' => 'bread_improver',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Bread improver for better texture and shelf life',
            ],

            // Salt
            [
                'type_code' => 'SALT',
                'type_name' => 'Iodized Salt',
                'type_name_am' => 'አዮዲን ጨው',
                'category' => 'salt',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Iodized table salt for baking',
            ],

            // Sugar
            [
                'type_code' => 'SUGAR-WHITE',
                'type_name' => 'White Sugar',
                'type_name_am' => 'ነጭ ስኳር',
                'category' => 'sugar',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Refined white sugar',
            ],
            [
                'type_code' => 'SUGAR-BROWN',
                'type_name' => 'Brown Sugar',
                'type_name_am' => 'ቡናማ ስኳር',
                'category' => 'sugar',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Brown sugar for cakes',
            ],

            // Oil
            [
                'type_code' => 'OIL-VEG',
                'type_name' => 'Vegetable Oil',
                'type_name_am' => 'የአትክልት ዘይት',
                'category' => 'oil',
                'default_unit_id' => $lUnit->id,
                'description' => 'Vegetable cooking oil',
            ],
            [
                'type_code' => 'OIL-PALM',
                'type_name' => 'Palm Oil',
                'type_name_am' => 'የፓልም ዘይት',
                'category' => 'oil',
                'default_unit_id' => $lUnit->id,
                'description' => 'Palm oil for frying',
            ],

            // Cake components
            [
                'type_code' => 'BUTTER',
                'type_name' => 'Butter',
                'type_name_am' => 'ቅቤ',
                'category' => 'cake_component',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Butter for cakes and pastries',
            ],
            [
                'type_code' => 'MARGARINE',
                'type_name' => 'Margarine',
                'type_name_am' => 'ማርጋሪን',
                'category' => 'cake_component',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Margarine for baking',
            ],
            [
                'type_code' => 'EGGS',
                'type_name' => 'Eggs',
                'type_name_am' => 'እንቁላል',
                'category' => 'cake_component',
                'default_unit_id' => $piecesUnit->id,
                'description' => 'Fresh eggs for baking',
            ],
            [
                'type_code' => 'MILK-PWD',
                'type_name' => 'Milk Powder',
                'type_name_am' => 'የወተት ዱቄት',
                'category' => 'cake_component',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Powdered milk for cakes',
            ],
            [
                'type_code' => 'VANILLA',
                'type_name' => 'Vanilla Extract',
                'type_name_am' => 'ቫኒላ',
                'category' => 'cake_component',
                'default_unit_id' => $lUnit->id,
                'description' => 'Vanilla flavoring',
            ],
            [
                'type_code' => 'BAKING-PWD',
                'type_name' => 'Baking Powder',
                'type_name_am' => 'የጋበዝ ዱቄት',
                'category' => 'cake_component',
                'default_unit_id' => $kgUnit->id,
                'description' => 'Baking powder for cakes',
            ],

            // Packaging
            [
                'type_code' => 'PKG-BAG-SM',
                'type_name' => 'Small Plastic Bag',
                'type_name_am' => 'ትንሽ ፕላስቲክ ቦርሳ',
                'category' => 'packaging',
                'default_unit_id' => $piecesUnit->id,
                'description' => 'Small plastic bags for individual bread',
            ],
            [
                'type_code' => 'PKG-BAG-LG',
                'type_name' => 'Large Plastic Bag',
                'type_name_am' => 'ትልቅ ፕላስቲክ ቦርሳ',
                'category' => 'packaging',
                'default_unit_id' => $piecesUnit->id,
                'description' => 'Large plastic bags for bulk',
            ],
            [
                'type_code' => 'PKG-BOX',
                'type_name' => 'Cake Box',
                'type_name_am' => 'የኬክ ሳጥን',
                'category' => 'packaging',
                'default_unit_id' => $piecesUnit->id,
                'description' => 'Cardboard boxes for cakes',
            ],
        ];

        foreach ($materialTypes as $materialType) {
            MaterialType::create($materialType);
        }

        $this->command->info('Material types seeded successfully!');
    }
}
