<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_activity_id');
            $table->unsignedInteger('user_id');
            $table->text('comment');
            $table->timestamps();

            $table->foreign('field_activity_id')->references('id')->on('field_activities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_activity_comments');
    }
};
