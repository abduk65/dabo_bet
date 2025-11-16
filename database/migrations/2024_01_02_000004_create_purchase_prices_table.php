<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->decimal('unit_price', 10, 2);
            $table->foreignId('unit_id')->constrained('units');
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->string('currency_code', 3)->default('ETB');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamps();

            $table->index(['inventory_item_id', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_prices');
    }
};
