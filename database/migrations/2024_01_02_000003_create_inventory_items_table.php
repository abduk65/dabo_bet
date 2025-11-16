<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code', 100)->unique();
            $table->foreignId('material_type_id')->constrained('material_types');
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('unit_of_measure_id')->constrained('units');
            $table->decimal('current_stock_quantity', 10, 3)->default(0);
            $table->decimal('reorder_level', 10, 3)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
