<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finished_goods_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('branch_id')->constrained('branches');
            $table->enum('transaction_type', ['production', 'dispatch', 'sales', 'return', 'waste', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->dateTime('transaction_date');
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable()->comment('COGS at time of transaction');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamps();

            $table->index(['product_id', 'branch_id', 'transaction_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finished_goods_inventory');
    }
};
