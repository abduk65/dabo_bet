<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [['saris', 'main'], ['tach_bet', 'sub']];

        foreach ($branches as $branch) {
            Branch::create([
                'name' => $branch[0],
                'type' => $branch[1],
            ]);
        }

    }
}
