<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_trade_name', function (Blueprint $table) {
            $table->foreignId('article_id')->unsigned()->constrained('articles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('trade_name_id')->unsigned()->constrained('ultrashop_trade_names')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['article_id', 'trade_name_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_trade_names');
    }
};
