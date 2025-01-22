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
        Schema::table('daily_sales', function (Blueprint $table) {
            $table->foreignId('daily_commission_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_sales', function (Blueprint $table) {
            $table->foreignId('commission_id');
            $table->float('commission_quantity');
        });
    }
};
