<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotAnnouncementViewsTable extends Migration
{
    public function up()
    {
        Schema::create('bot_announcement_views', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->index();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('phone_number');
            $table->string('session_id')->nullable();
            $table->timestamp('viewed_at');
            $table->string('platform')->nullable(); 
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['message_id', 'phone_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_announcement_views');
    }
}