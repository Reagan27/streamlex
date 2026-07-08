<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('education_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('other_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_documents');
        Schema::dropIfExists('other_documents');
    }
};
