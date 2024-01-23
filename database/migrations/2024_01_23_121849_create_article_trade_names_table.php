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
        Schema::create('article_trade_names', function (Blueprint $table) {
            $table->bigInteger('article_id')->unsigned();
            $table->bigInteger('trade_name_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('trade_name_id')->references('id')->on('ultrashop_trade_names')->onUpdate('cascade')->onDelete('cascade');
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
