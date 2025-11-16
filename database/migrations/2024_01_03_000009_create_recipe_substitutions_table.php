<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_substitutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders');
            $table->foreignId('original_recipe_component_id')->constrained('recipe_components');
            $table->foreignId('original_inventory_item_id')->constrained('inventory_items');
            $table->foreignId('substitute_inventory_item_id')->constrained('inventory_items');
            $table->text('substitution_reason');
            $table->foreignId('approved_by_user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['production_order_id', 'original_inventory_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_substitutions');
    }
};
