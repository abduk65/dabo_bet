<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_code', 50)->unique();
            $table->string('type_name', 100);
            $table->string('type_name_am', 100)->nullable()->comment('Amharic translation');
            $table->enum('category', ['flour', 'ersho', 'bread_improver', 'salt', 'sugar', 'oil', 'cake_component', 'packaging', 'other']);
            $table->foreignId('base_unit_id')->constrained('units');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_types');
    }
};
