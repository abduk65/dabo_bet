<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standard_batch_varieties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->decimal('single_factor_expected_output', 10, 2)->comment('Expected yield with 1x scaling');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standard_batch_varieties');
    }
};
