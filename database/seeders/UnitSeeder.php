<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            // Weight units
            ['unit_name' => 'Kilogram', 'unit_abbreviation' => 'kg', 'unit_type' => 'weight'],
            ['unit_name' => 'Gram', 'unit_abbreviation' => 'g', 'unit_type' => 'weight'],
            ['unit_name' => 'Quintal', 'unit_abbreviation' => 'q', 'unit_type' => 'weight'],

            // Volume units
            ['unit_name' => 'Liter', 'unit_abbreviation' => 'L', 'unit_type' => 'volume'],
            ['unit_name' => 'Milliliter', 'unit_abbreviation' => 'mL', 'unit_type' => 'volume'],

            // Count units
            ['unit_name' => 'Piece', 'unit_abbreviation' => 'pcs', 'unit_type' => 'count'],
            ['unit_name' => 'Pack', 'unit_abbreviation' => 'pk', 'unit_type' => 'count'],
            ['unit_name' => 'Box', 'unit_abbreviation' => 'box', 'unit_type' => 'count'],
            ['unit_name' => 'Tray', 'unit_abbreviation' => 'tray', 'unit_type' => 'count'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
