<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_material_consumption', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->foreignId('recipe_component_id')->constrained('recipe_components');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->comment('Actual material used (may differ from recipe if substituted)');
            $table->decimal('planned_quantity', 10, 3);
            $table->decimal('actual_quantity_used', 10, 3)->nullable();
            $table->decimal('variance_quantity', 10, 3)->nullable();
            $table->enum('variance_reason', ['normal_waste', 'spillage', 'quality_issue', 'theft', 'other'])->nullable();
            $table->decimal('unit_cost', 10, 2)->comment('Price at time of production');
            $table->decimal('total_cost', 12, 2);
            $table->boolean('is_substitution')->default(false);
            $table->foreignId('recorded_by_user_id')->constrained('users');
            $table->timestamps();

            $table->index(['production_order_id', 'inventory_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_material_consumption');
    }
};
