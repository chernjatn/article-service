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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('second_name');
            $table->string('slug');
            $table->string('place_of_work');
            $table->string('education');
            $table->string('experience');
            $table->string('speciality');
            $table->boolean('status')->default(false);
            $table->enum('gender', ['m','f']);
            $table->timestamps();

            $table->foreignId('seo_id')->constrained()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
