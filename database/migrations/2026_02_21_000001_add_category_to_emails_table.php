<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToEmailsTable extends Migration
{
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('category')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}
