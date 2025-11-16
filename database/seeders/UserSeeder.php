<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        User::create([
            'name' => 'Business Owner',
            'email' => 'owner@bakery.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'branch_id' => 1, // Main branch
            'is_active' => true,
        ]);

        // Manager
        User::create([
            'name' => 'Production Manager',
            'email' => 'manager@bakery.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'branch_id' => 1, // Main branch
            'is_active' => true,
        ]);

        // Supervisor
        User::create([
            'name' => 'Branch Supervisor',
            'email' => 'supervisor@bakery.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'branch_id' => 2, // Bole branch
            'is_active' => true,
        ]);

        // Employee
        User::create([
            'name' => 'Branch Employee',
            'email' => 'employee@bakery.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'branch_id' => 2, // Bole branch
            'is_active' => true,
        ]);
    }
}
