<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberOfCoachesToCoachRequisitionsTable extends Migration
{
    public function up()
    {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            $table->integer('number_of_coaches')->default(1)->after('position_title');
        });
    }

    public function down()
    {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            $table->dropColumn('number_of_coaches');
        });
    }
}