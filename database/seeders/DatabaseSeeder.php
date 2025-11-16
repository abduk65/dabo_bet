<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // Foundation
            UnitSeeder::class,
            BranchSeeder::class,
            UserSeeder::class,

            // Phase 1: Inventory
            MaterialTypeSeeder::class,
            BrandSeeder::class,

            // Phase 2: Production
            ProductSeeder::class,

            // Phase 3: Sales
            CustomerSeeder::class,

            // Phase 4: Financial
            AccountSeeder::class,
        ]);
    }
}
