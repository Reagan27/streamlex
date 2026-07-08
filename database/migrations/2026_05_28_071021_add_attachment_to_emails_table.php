<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('emails', function (Blueprint $table) {
        $table->string('attachment')->nullable()->after('status');
        $table->string('attachment_original_name')->nullable()->after('attachment');
    });
}

public function down()
{
    Schema::table('emails', function (Blueprint $table) {
        $table->dropColumn(['attachment', 'attachment_original_name']);
    });
}
};
