<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['purchase', 'production_consumption', 'adjustment', 'transfer', 'waste']);
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->decimal('quantity', 10, 3);
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->dateTime('transaction_date');
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['inventory_item_id', 'transaction_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
