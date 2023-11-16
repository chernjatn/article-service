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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('channel', 20)->index();
            $table->integer('wp_article_id')->nullable()->index();
            $table->string('title');
            $table->string('slug');
            $table->json('product_ids');
            $table->longText('content');
            $table->longText('excerpt');
            $table->boolean('in_slider')->default(false);
            $table->boolean('status')->default(false);
            $table->boolean('noindex')->default(false);

            $table->foreignId('author_id')->constrained()
                ->cascadeOnUpdate();
            $table->foreignId('heading_id')->constrained()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
