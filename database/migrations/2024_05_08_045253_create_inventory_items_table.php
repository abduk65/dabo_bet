<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->foreignId('brand_id');
            $table->foreignId('unit_id');
            $table->float('quantity');
            $table->float('price');
            $table->float('total_price');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    //TODO: Add a batch malekiya nullable date

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
