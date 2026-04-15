<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_departures', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('item_master')->nullable();
            $table->decimal('prev_stock', 15, 2)->default(0.00);
            $table->decimal('departure_qnty', 15, 2)->default(0.00);
            $table->decimal('balance_qnty', 15, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_departures');
    }
};
