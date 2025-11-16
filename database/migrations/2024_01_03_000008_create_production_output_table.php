<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_output', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('quantity_produced', 10, 2);
            $table->decimal('quantity_good', 10, 2)->comment('Passed quality control');
            $table->decimal('quantity_rejected', 10, 2)->default(0);
            $table->enum('rejection_reason', ['burnt', 'undercooked', 'contaminated', 'damaged', 'other'])->nullable();
            $table->decimal('unit_production_cost', 10, 2)->nullable()->comment('COGS per unit');
            $table->foreignId('recorded_by_user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_output');
    }
};
