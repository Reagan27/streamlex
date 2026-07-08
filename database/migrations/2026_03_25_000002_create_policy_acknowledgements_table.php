<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('policy_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index(); // ← matches increments('id')
            $table->string('employee_name');
            $table->timestamp('acknowledged_at');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('policy_acknowledgements');
    }
};