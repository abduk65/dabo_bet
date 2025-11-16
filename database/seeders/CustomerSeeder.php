<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boleBranch = Branch::where('name', 'like', '%Bole%')->first();
        $piassaBranch = Branch::where('name', 'like', '%Piassa%')->first();

        $customers = [
            // Walk-in customers (for cash sales)
            [
                'name' => 'Walk-in Customer - Bole',
                'type' => 'walk_in',
                'branch_id' => $boleBranch?->id,
                'phone' => null,
                'email' => null,
                'address' => null,
                'tin_number' => null,
                'credit_limit' => 0,
                'credit_period_days' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Walk-in Customer - Piassa',
                'type' => 'walk_in',
                'branch_id' => $piassaBranch?->id,
                'phone' => null,
                'email' => null,
                'address' => null,
                'tin_number' => null,
                'credit_limit' => 0,
                'credit_period_days' => 0,
                'is_active' => true,
            ],

            // Commission recipients (bulk buyers with contract pricing)
            [
                'name' => 'Sheraton Addis Hotel',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-517-1717',
                'email' => 'procurement@sheraton-addis.com',
                'address' => 'Taitu Street, Addis Ababa',
                'tin_number' => 'TIN-0001234567',
                'credit_limit' => 100000.00,
                'credit_period_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Hilton Hotel Addis',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-518-4000',
                'email' => 'purchasing@hilton-addis.com',
                'address' => 'Menelik II Avenue, Addis Ababa',
                'tin_number' => 'TIN-0001234568',
                'credit_limit' => 150000.00,
                'credit_period_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Capital Hotel & Spa',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-470-0000',
                'email' => 'procurement@capitalhotel.et',
                'address' => 'Bole Road, Addis Ababa',
                'tin_number' => 'TIN-0001234569',
                'credit_limit' => 75000.00,
                'credit_period_days' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Ethiopian Airlines Catering',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-665-5000',
                'email' => 'catering@ethiopianairlines.com',
                'address' => 'Bole International Airport',
                'tin_number' => 'TIN-0001234570',
                'credit_limit' => 200000.00,
                'credit_period_days' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Burger King Ethiopia',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-662-2000',
                'email' => 'supply@burgerking.et',
                'address' => 'Bole Medhanialem, Addis Ababa',
                'tin_number' => 'TIN-0001234571',
                'credit_limit' => 50000.00,
                'credit_period_days' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Pizza Hut Ethiopia',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-618-3000',
                'email' => 'procurement@pizzahut.et',
                'address' => 'CMC Area, Addis Ababa',
                'tin_number' => 'TIN-0001234572',
                'credit_limit' => 40000.00,
                'credit_period_days' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Addis Ababa University Cafeteria',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-123-9570',
                'email' => 'cafeteria@aau.edu.et',
                'address' => 'Sidist Kilo Campus, Addis Ababa',
                'tin_number' => 'TIN-0001234573',
                'credit_limit' => 80000.00,
                'credit_period_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Bole International Hospital',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-667-8000',
                'email' => 'supplies@bolehospital.com',
                'address' => 'Bole Subcity, Addis Ababa',
                'tin_number' => 'TIN-0001234574',
                'credit_limit' => 60000.00,
                'credit_period_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Yohannes Minimart Chain',
                'type' => 'commission_recipient',
                'branch_id' => null,
                'phone' => '+251-11-557-0000',
                'email' => 'procurement@yohannesminimart.et',
                'address' => 'Multiple Locations, Addis Ababa',
                'tin_number' => 'TIN-0001234575',
                'credit_limit' => 90000.00,
                'credit_period_days' => 15,
                'is_active' => true,
            ],

            // Branch customers (sub-branches of the bakery)
            [
                'name' => 'Bole Retail Branch',
                'type' => 'branch',
                'branch_id' => $boleBranch?->id,
                'phone' => '+251-11-618-0001',
                'email' => 'bole@bakery.com',
                'address' => 'Bole Area, Addis Ababa',
                'tin_number' => null,
                'credit_limit' => 0,
                'credit_period_days' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Piassa Retail Branch',
                'type' => 'branch',
                'branch_id' => $piassaBranch?->id,
                'phone' => '+251-11-518-0002',
                'email' => 'piassa@bakery.com',
                'address' => 'Piassa Area, Addis Ababa',
                'tin_number' => null,
                'credit_limit' => 0,
                'credit_period_days' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('Customers seeded successfully!');
    }
}
