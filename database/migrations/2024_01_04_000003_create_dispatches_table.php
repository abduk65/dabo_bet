<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('dispatch_number', 50)->unique();
            $table->foreignId('from_branch_id')->constrained('branches')->comment('Usually main production branch');
            $table->foreignId('to_branch_id')->constrained('branches')->comment('Sub-branch receiving goods');
            $table->date('dispatch_date');
            $table->enum('status', ['draft', 'dispatched', 'received', 'cancelled'])->default('draft');
            $table->dateTime('dispatched_at')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->foreignId('dispatched_by_user_id')->nullable()->constrained('users');
            $table->foreignId('received_by_user_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['from_branch_id', 'to_branch_id', 'dispatch_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
