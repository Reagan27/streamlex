<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('first_authentication')
              ->default(true)
              ->after('email_verified_at');

        $table->boolean('initial_password')
              ->default(true)
              ->after('first_authentication');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['first_authentication', 'initial_password']);
    });
}
};
