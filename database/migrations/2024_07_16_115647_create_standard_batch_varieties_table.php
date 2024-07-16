<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standard_batch_varieties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('default');
            $table->foreignId('recipe_id');
            $table->float('single_factor_expected_output');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_batch_varieties');
    }
};
