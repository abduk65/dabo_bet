<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number', 50)->unique();
            $table->date('entry_date');
            $table->enum('entry_type', [
                'purchase', 'production', 'dispatch', 'sale', 'payment',
                'expense', 'depreciation', 'adjustment', 'opening_balance'
            ])->comment('Type of transaction that generated this entry');
            $table->string('reference_type', 50)->nullable()->comment('Polymorphic reference to source transaction');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->boolean('is_balanced')->default(false)->comment('Debit = Credit check');
            $table->enum('status', ['draft', 'posted', 'reversed'])->default('draft');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->foreignId('posted_by_user_id')->nullable()->constrained('users');
            $table->dateTime('posted_at')->nullable();
            $table->timestamps();

            $table->index(['entry_date', 'status']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('entry_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
