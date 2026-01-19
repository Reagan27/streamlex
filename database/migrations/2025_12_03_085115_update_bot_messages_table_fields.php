<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotMessagesTableFields extends Migration
{
    public function up()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            // Add missing email field
            if (!Schema::hasColumn('bot_messages', 'recipient')) {
                $table->string('recipient')->nullable()->after('phone');
            }

            // Add any other missing fields here to match messages table
            if (!Schema::hasColumn('bot_messages', 'category')) {
                $table->string('category')->nullable()->after('recipient');
            }

            if (!Schema::hasColumn('bot_messages', 'status_message')) {
                $table->string('status_message')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            if (Schema::hasColumn('bot_messages', 'recipient')) {
                $table->dropColumn('recipient');
            }

            if (Schema::hasColumn('bot_messages', 'category')) {
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('bot_messages', 'status_message')) {
                $table->dropColumn('status_message');
            }
        });
    }
}
