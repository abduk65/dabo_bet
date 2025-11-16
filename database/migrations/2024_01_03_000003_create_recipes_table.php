<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('recipe_code', 50)->unique();
            $table->foreignId('product_id')->constrained('products');
            $table->string('recipe_name', 100);
            $table->string('version_number', 20)->nullable();
            $table->decimal('expected_yield_quantity', 10, 2);
            $table->foreignId('yield_unit_id')->constrained('units');
            $table->boolean('is_active')->default(true);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
