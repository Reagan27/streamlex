<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('info_accurate')->default(false);
            $table->boolean('info_authorize')->default(false);
            $table->boolean('info_falsified')->default(false);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['info_accurate', 'info_authorize', 'info_falsified']);
        });
    }
};
