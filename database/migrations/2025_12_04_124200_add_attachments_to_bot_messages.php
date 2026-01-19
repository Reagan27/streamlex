<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentsToBotMessages extends Migration
{
    public function up()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('message');
        });
    }

    public function down()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
}