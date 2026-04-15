<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_entry_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_entry_id');
            $table->string('item_master')->nullable();
            $table->decimal('old_stock', 15, 2)->default(0.00);
            $table->decimal('qnty', 15, 2)->default(0.00);
            $table->decimal('new_stock', 15, 2)->default(0.00);
            $table->decimal('purchase_rate', 15, 2)->default(0.00);
            $table->decimal('total_price', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('stock_entry_id')->references('id')->on('stock_entries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_entry_details');
    }
};
