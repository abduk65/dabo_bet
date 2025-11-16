<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code', 20)->unique();
            $table->string('account_name', 100);
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense', 'cogs'])->comment('Chart of accounts classification');
            $table->enum('sub_type', [
                'cash', 'bank', 'accounts_receivable', 'inventory', 'fixed_asset',
                'accounts_payable', 'loan', 'equity',
                'sales_revenue', 'other_income',
                'operating_expense', 'cogs'
            ])->nullable();
            $table->foreignId('parent_account_id')->nullable()->constrained('accounts')->comment('For hierarchical chart of accounts');
            $table->enum('normal_balance', ['debit', 'credit'])->comment('Normal balance side for this account');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system_account')->default(false)->comment('System-generated accounts cannot be deleted');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['account_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
