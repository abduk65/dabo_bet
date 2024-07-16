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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_batch_variety_id');
            $table->float('variety_factor')->default(1);
            $table->float('output_count');
            $table->timestamps();
        });

        // TODO ON EVERY SCHEMA LOOK FOR THE FOREIGN RELATIONSHIPS AND THINK OF WHAT HAPPENS WHEN THE SHIT GETS DELETED MNAMN
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
