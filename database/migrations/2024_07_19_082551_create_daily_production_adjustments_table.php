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
        Schema::create('daily_production_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->float('quantity');
            $table->foreignId('unit_id');
            $table->enum('type', ['damaged', 'stale', 'worker_error']);
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_production_adjustments');
    }
};
