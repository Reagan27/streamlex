<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingFieldsToBotMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->boolean('is_rating')->default(false)->after('message');
            $table->string('rating_type')->nullable()->after('is_rating'); // 'thumbs' or 'scale'
            $table->integer('scale_min')->nullable()->after('rating_type');
            $table->integer('scale_max')->nullable()->after('scale_min');
            $table->boolean('allow_comment')->default(false)->after('scale_max');
            $table->boolean('allow_skip')->default(true)->after('allow_comment');
        });
    }

    public function down()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->dropColumn([
                'is_rating',
                'rating_type',
                'scale_min',
                'scale_max',
                'allow_comment',
                'allow_skip'
            ]);
        });
    }
}