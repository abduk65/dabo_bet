<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Main Production Branch',
                'type' => 'main',
                'address' => 'Addis Ababa, Ethiopia',
                'phone' => '+251911234567',
                'is_active' => true,
            ],
            [
                'name' => 'Bole Branch',
                'type' => 'sub',
                'address' => 'Bole, Addis Ababa',
                'phone' => '+251911234568',
                'is_active' => true,
            ],
            [
                'name' => 'Piassa Branch',
                'type' => 'sub',
                'address' => 'Piassa, Addis Ababa',
                'phone' => '+251911234569',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
