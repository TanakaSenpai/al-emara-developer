<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_logs', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->foreignId('account_id')->constrained('account_masters')->onDelete('cascade');
            $table->string('transaction_type'); // e.g., 'Daily Expense', 'Bill Payment', 'Partner Collection', 'Manual Adjustment'
            $table->text('description')->nullable();
            $table->decimal('debit_amount', 15, 2)->default(0); // Money going out
            $table->decimal('credit_amount', 15, 2)->default(0); // Money coming in
            $table->decimal('balance_after', 15, 2); // Account balance after this transaction
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the related record
            $table->string('reference_type')->nullable(); // Model class name of the related record
            $table->timestamps();

            $table->index(['account_id', 'transaction_date']);
            $table->index(['reference_id', 'reference_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_logs');
    }
};
