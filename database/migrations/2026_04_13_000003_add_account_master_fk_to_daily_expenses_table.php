<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_expenses', function (Blueprint $table) {
            $table->foreign('account_id')
                ->references('id')
                ->on('account_masters')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('daily_expenses', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });
    }
};
