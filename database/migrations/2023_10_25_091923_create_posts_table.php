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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->integer('channel_id')->index();
            $table->integer('wp_post_id')->index();
            $table->string('title');
            $table->string('author')->default(null);
            $table->text('heading');
            $table->json('content');
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
        Schema::dropIfExists('posts');
    }
};
