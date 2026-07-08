<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rateable_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->boolean('status')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rateable_items');
    }
};