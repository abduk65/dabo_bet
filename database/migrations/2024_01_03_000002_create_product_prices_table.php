<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->comment('NULL = all branches');
            $table->enum('price_type', ['standard', 'wholesale', 'commission_recipient'])->default('standard');
            $table->decimal('unit_price', 10, 2);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamps();

            $table->index(['product_id', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
