<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->string('expense_details');
            $table->decimal('expense_amount', 10, 2);
            $table->string('expense_by')->nullable();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_expenses');
    }
};
