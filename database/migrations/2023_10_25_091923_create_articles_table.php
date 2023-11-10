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

            $table->integer('channel_id')->index();
            $table->integer('wp_article_id')->nullable()->index();
            $table->string('title');
            $table->foreignId('author_id')->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('heading_id')->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->longText('content');
            $table->text('good_ids');
            $table->boolean('status')->default(false);
            $table->boolean('noindex')->default(false);

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
