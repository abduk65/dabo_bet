<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2)->comment('Selling price at time of sale');
            $table->decimal('unit_cost', 10, 2)->comment('COGS snapshot - CRITICAL for profit calculation');
            $table->decimal('line_total', 12, 2);
            $table->decimal('line_profit', 12, 2)->nullable()->comment('Calculated: (unit_price - unit_cost) * quantity');
            $table->timestamps();

            $table->index(['sale_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
