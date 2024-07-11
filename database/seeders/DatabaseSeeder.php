<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\DailyInventoryOut;
use App\Models\InventoryItem;
use App\Models\ProductType;
use App\Models\User;
use App\Models\Unit;
use Database\Factories\InventoryItemFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->withPersonalTeam()->create();

        // User::factory()->withPersonalTeam()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UnitSeeder::class);
        $this->call(ProductTypeSeeder::class);
        Brand::factory()->count(10)->create();
        InventoryItem::factory()->count(40)->create();
        DailyInventoryOut::factory()->count(50)->create();
    }
}
