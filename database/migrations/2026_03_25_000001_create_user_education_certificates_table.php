<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_education_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('level')->nullable(); // Secondary, College, University, Postgraduate, etc.
            $table->string('institution')->nullable();
            $table->string('award')->nullable();
            $table->string('year')->nullable();
            $table->string('file_path'); // Certificate file
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_education_certificates');
    }
};
