<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // ASSETS
            [
                'account_code' => '1000',
                'account_name' => 'Assets',
                'account_type' => 'asset',
                'sub_type' => null,
                'parent_account_id' => null,
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'All company assets',
            ],
            [
                'account_code' => '1100',
                'account_name' => 'Current Assets',
                'account_type' => 'asset',
                'sub_type' => null,
                'parent_account_id' => null, // Will be set to 1000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Assets expected to be converted to cash within one year',
            ],
            [
                'account_code' => '1110',
                'account_name' => 'Cash on Hand',
                'account_type' => 'asset',
                'sub_type' => 'cash',
                'parent_account_id' => null, // Will be set to 1100
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Physical cash at branches',
            ],
            [
                'account_code' => '1120',
                'account_name' => 'Cash in Bank',
                'account_type' => 'asset',
                'sub_type' => 'bank',
                'parent_account_id' => null, // Will be set to 1100
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Bank account balances',
            ],
            [
                'account_code' => '1200',
                'account_name' => 'Accounts Receivable',
                'account_type' => 'asset',
                'sub_type' => 'accounts_receivable',
                'parent_account_id' => null, // Will be set to 1100
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Money owed by customers (credit sales)',
            ],
            [
                'account_code' => '1300',
                'account_name' => 'Raw Materials Inventory',
                'account_type' => 'asset',
                'sub_type' => 'inventory',
                'parent_account_id' => null, // Will be set to 1100
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Inventory of raw materials (flour, sugar, etc.)',
            ],
            [
                'account_code' => '1310',
                'account_name' => 'Finished Goods Inventory',
                'account_type' => 'asset',
                'sub_type' => 'inventory',
                'parent_account_id' => null, // Will be set to 1100
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Inventory of finished products (bread, cakes)',
            ],
            [
                'account_code' => '1500',
                'account_name' => 'Fixed Assets',
                'account_type' => 'asset',
                'sub_type' => 'fixed_asset',
                'parent_account_id' => null, // Will be set to 1000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Long-term assets (equipment, buildings)',
            ],

            // LIABILITIES
            [
                'account_code' => '2000',
                'account_name' => 'Liabilities',
                'account_type' => 'liability',
                'sub_type' => null,
                'parent_account_id' => null,
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'All company liabilities',
            ],
            [
                'account_code' => '2100',
                'account_name' => 'Current Liabilities',
                'account_type' => 'liability',
                'sub_type' => null,
                'parent_account_id' => null, // Will be set to 2000
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Liabilities due within one year',
            ],
            [
                'account_code' => '2110',
                'account_name' => 'Accounts Payable',
                'account_type' => 'liability',
                'sub_type' => 'accounts_payable',
                'parent_account_id' => null, // Will be set to 2100
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Money owed to suppliers',
            ],

            // EQUITY
            [
                'account_code' => '3000',
                'account_name' => 'Equity',
                'account_type' => 'equity',
                'sub_type' => 'equity',
                'parent_account_id' => null,
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Owner\'s equity',
            ],
            [
                'account_code' => '3100',
                'account_name' => 'Owner\'s Capital',
                'account_type' => 'equity',
                'sub_type' => 'equity',
                'parent_account_id' => null, // Will be set to 3000
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Initial capital invested by owner',
            ],
            [
                'account_code' => '3200',
                'account_name' => 'Retained Earnings',
                'account_type' => 'equity',
                'sub_type' => 'equity',
                'parent_account_id' => null, // Will be set to 3000
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Accumulated profits',
            ],

            // REVENUE
            [
                'account_code' => '4000',
                'account_name' => 'Revenue',
                'account_type' => 'revenue',
                'sub_type' => 'sales_revenue',
                'parent_account_id' => null,
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'All revenue accounts',
            ],
            [
                'account_code' => '4100',
                'account_name' => 'Sales Revenue - Bread',
                'account_type' => 'revenue',
                'sub_type' => 'sales_revenue',
                'parent_account_id' => null, // Will be set to 4000
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Revenue from bread sales',
            ],
            [
                'account_code' => '4200',
                'account_name' => 'Sales Revenue - Cake',
                'account_type' => 'revenue',
                'sub_type' => 'sales_revenue',
                'parent_account_id' => null, // Will be set to 4000
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Revenue from cake sales',
            ],
            [
                'account_code' => '4300',
                'account_name' => 'Sales Revenue - Others',
                'account_type' => 'revenue',
                'sub_type' => 'sales_revenue',
                'parent_account_id' => null, // Will be set to 4000
                'normal_balance' => 'credit',
                'is_system_account' => true,
                'description' => 'Revenue from other product sales',
            ],

            // COST OF GOODS SOLD
            [
                'account_code' => '5000',
                'account_name' => 'Cost of Goods Sold',
                'account_type' => 'cogs',
                'sub_type' => 'cogs',
                'parent_account_id' => null,
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Direct costs of producing goods sold',
            ],
            [
                'account_code' => '5100',
                'account_name' => 'COGS - Raw Materials',
                'account_type' => 'cogs',
                'sub_type' => 'cogs',
                'parent_account_id' => null, // Will be set to 5000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Cost of raw materials used in production',
            ],
            [
                'account_code' => '5200',
                'account_name' => 'COGS - Direct Labor',
                'account_type' => 'cogs',
                'sub_type' => 'cogs',
                'parent_account_id' => null, // Will be set to 5000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Direct labor costs for production',
            ],

            // EXPENSES
            [
                'account_code' => '6000',
                'account_name' => 'Operating Expenses',
                'account_type' => 'expense',
                'sub_type' => 'operating_expense',
                'parent_account_id' => null,
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'All operating expenses',
            ],
            [
                'account_code' => '6100',
                'account_name' => 'Salaries and Wages',
                'account_type' => 'expense',
                'sub_type' => 'operating_expense',
                'parent_account_id' => null, // Will be set to 6000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Employee salaries and wages',
            ],
            [
                'account_code' => '6200',
                'account_name' => 'Rent Expense',
                'account_type' => 'expense',
                'sub_type' => 'operating_expense',
                'parent_account_id' => null, // Will be set to 6000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Rent for facilities',
            ],
            [
                'account_code' => '6300',
                'account_name' => 'Utilities Expense',
                'account_type' => 'expense',
                'sub_type' => 'operating_expense',
                'parent_account_id' => null, // Will be set to 6000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Electricity, water, gas',
            ],
            [
                'account_code' => '6400',
                'account_name' => 'Transportation Expense',
                'account_type' => 'expense',
                'sub_type' => 'operating_expense',
                'parent_account_id' => null, // Will be set to 6000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Transportation and delivery costs',
            ],
            [
                'account_code' => '6500',
                'account_name' => 'Depreciation Expense',
                'account_type' => 'expense',
                'sub_type' => 'operating_expense',
                'parent_account_id' => null, // Will be set to 6000
                'normal_balance' => 'debit',
                'is_system_account' => true,
                'description' => 'Depreciation of fixed assets',
            ],
        ];

        // Create accounts and build parent relationships
        $createdAccounts = [];

        foreach ($accounts as $accountData) {
            $account = Account::create($accountData);
            $createdAccounts[$accountData['account_code']] = $account;
        }

        // Set parent relationships based on account hierarchy
        $this->setParentRelationships($createdAccounts);

        $this->command->info('Chart of Accounts seeded successfully!');
    }

    /**
     * Set parent account relationships
     */
    private function setParentRelationships(array $accounts): void
    {
        $relationships = [
            '1100' => '1000', // Current Assets -> Assets
            '1110' => '1100', // Cash on Hand -> Current Assets
            '1120' => '1100', // Cash in Bank -> Current Assets
            '1200' => '1100', // Accounts Receivable -> Current Assets
            '1300' => '1100', // Raw Materials Inventory -> Current Assets
            '1310' => '1100', // Finished Goods Inventory -> Current Assets
            '1500' => '1000', // Fixed Assets -> Assets
            '2100' => '2000', // Current Liabilities -> Liabilities
            '2110' => '2100', // Accounts Payable -> Current Liabilities
            '3100' => '3000', // Owner's Capital -> Equity
            '3200' => '3000', // Retained Earnings -> Equity
            '4100' => '4000', // Sales Revenue - Bread -> Revenue
            '4200' => '4000', // Sales Revenue - Cake -> Revenue
            '4300' => '4000', // Sales Revenue - Others -> Revenue
            '5100' => '5000', // COGS - Raw Materials -> COGS
            '5200' => '5000', // COGS - Direct Labor -> COGS
            '6100' => '6000', // Salaries and Wages -> Operating Expenses
            '6200' => '6000', // Rent Expense -> Operating Expenses
            '6300' => '6000', // Utilities Expense -> Operating Expenses
            '6400' => '6000', // Transportation Expense -> Operating Expenses
            '6500' => '6000', // Depreciation Expense -> Operating Expenses
        ];

        foreach ($relationships as $childCode => $parentCode) {
            if (isset($accounts[$childCode]) && isset($accounts[$parentCode])) {
                $accounts[$childCode]->parent_account_id = $accounts[$parentCode]->id;
                $accounts[$childCode]->save();
            }
        }
    }
}
