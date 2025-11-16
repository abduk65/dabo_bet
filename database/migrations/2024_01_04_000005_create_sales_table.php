<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number', 50)->unique();
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('sale_date');
            $table->enum('payment_type', ['cash', 'credit', 'bank_transfer'])->default('cash');
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->date('due_date')->nullable()->comment('For credit sales');
            $table->enum('status', ['draft', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'sale_date']);
            $table->index(['customer_id', 'status']);
            $table->index('payment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
