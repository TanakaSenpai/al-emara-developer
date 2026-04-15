<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_masters', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name');
            $table->string('mobile', 50)->nullable();
            $table->text('address')->nullable();
            $table->decimal('due_amount', 15, 2)->default(0.00);
            $table->decimal('extra_amount', 15, 2)->default(0.00);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->decimal('total_charges', 15, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_masters');
    }
};
