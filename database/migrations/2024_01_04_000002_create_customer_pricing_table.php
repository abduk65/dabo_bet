<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('price_per_unit', 10, 2);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable()->comment('NULL = current pricing');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamps();

            $table->index(['customer_id', 'product_id', 'effective_date']);
            $table->index(['effective_date', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_pricing');
    }
};
