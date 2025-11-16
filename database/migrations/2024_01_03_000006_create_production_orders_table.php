<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_order_number', 50)->unique();
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->date('production_date');
            $table->enum('shift', ['morning', 'afternoon', 'night'])->nullable();
            $table->decimal('planned_quantity', 10, 2);
            $table->decimal('actual_quantity_produced', 10, 2)->nullable();
            $table->decimal('scaling_factor', 10, 2)->default(1.0);
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->decimal('production_cost_total', 12, 2)->nullable();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->foreignId('started_by_user_id')->nullable()->constrained('users');
            $table->dateTime('started_at')->nullable();
            $table->foreignId('completed_by_user_id')->nullable()->constrained('users');
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['production_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
