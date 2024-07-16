<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\DailyInventoryOut;
use App\Models\InventoryItem;
use App\Models\ProductType;
use App\Models\Recipe;
use App\Models\RecipeInventoryItem;
use App\Models\User;
use App\Models\Unit;
use App\Models\WorkOrder;
use Database\Factories\InventoryItemFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\RecipeInventoryItemFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'role' => 'admin',

        ]);

        $this->call(UnitSeeder::class);
        $this->call(ProductTypeSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(ProductSeeder::class);
        Brand::factory()->count(10)->create();
        InventoryItem::factory()->count(40)->create();
        DailyInventoryOut::factory()->count(50)->create();
        Recipe::factory()->count(10)->create();
        RecipeInventoryItem::factory()->count(50)->create();

        for ($i = 0; $i < 10; $i++) {
            $this->call(StandardBatchVarietySeeder::class);
        }

        WorkOrder::factory()->count(50)->create();

    }
}
