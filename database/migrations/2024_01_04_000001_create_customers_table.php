<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->unique();
            $table->string('name', 100);
            $table->enum('type', ['walk_in', 'commission_recipient', 'branch'])->comment('walk_in = cash sales, commission_recipient = contract pricing, branch = sub-branch');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->comment('Only populated if type=branch');
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('tin_number', 50)->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable()->default(0);
            $table->integer('credit_period_days')->nullable()->default(0)->comment('Payment terms in days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
