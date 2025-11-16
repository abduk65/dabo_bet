<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->comment('Brand-specific material');
            $table->decimal('quantity_required', 10, 3);
            $table->foreignId('unit_id')->constrained('units');
            $table->integer('sequence_order')->nullable()->comment('Order for mixing instructions');
            $table->boolean('is_substitutable')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['recipe_id', 'sequence_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_components');
    }
};
