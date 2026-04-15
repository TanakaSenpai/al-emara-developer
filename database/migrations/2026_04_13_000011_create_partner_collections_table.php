<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_collections', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('partners')->nullable();
            $table->string('budget_plan')->nullable();
            $table->string('account_master')->nullable();
            $table->decimal('total_due_balance', 15, 2)->default(0.00);
            $table->decimal('total_paid_amount', 15, 2)->default(0.00);
            $table->decimal('extra_charges', 15, 2)->default(0.00);
            $table->decimal('net_amount', 15, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_collections');
    }
};
