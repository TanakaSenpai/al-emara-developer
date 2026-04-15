<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_masters', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('mobile', 50)->nullable();
            $table->text('address')->nullable();
            $table->decimal('due_balance', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_masters');
    }
};
