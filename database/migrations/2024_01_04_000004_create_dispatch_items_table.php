<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispatch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained('dispatches')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('quantity_dispatched', 10, 2);
            $table->decimal('quantity_received', 10, 2)->nullable()->comment('May differ from dispatched due to damage in transit');
            $table->decimal('unit_cost', 10, 2)->comment('COGS snapshot at dispatch time - CRITICAL for P&L');
            $table->decimal('damage_quantity', 10, 2)->nullable()->default(0);
            $table->text('damage_notes')->nullable();
            $table->timestamps();

            $table->index(['dispatch_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_items');
    }
};
