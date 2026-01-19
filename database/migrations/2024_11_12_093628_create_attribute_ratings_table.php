<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('rateable_attribute_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('rating');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_ratings');
    }
};