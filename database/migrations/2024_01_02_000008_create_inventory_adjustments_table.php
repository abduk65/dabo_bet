<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->enum('adjustment_type', ['count_correction', 'damage', 'theft', 'system_error', 'price_correction']);
            $table->decimal('quantity', 10, 3);
            $table->foreignId('unit_id')->constrained('units');
            $table->enum('operation', ['addition', 'subtraction']);
            $table->text('reason')->nullable();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
    }
};
