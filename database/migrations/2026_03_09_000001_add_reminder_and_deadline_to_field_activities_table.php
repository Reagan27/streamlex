<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('field_activities', function (Blueprint $table) {
            $table->date('reminder_date')->nullable()->after('end_date');
            $table->date('deadline')->nullable()->after('reminder_date');
        });
    }

    public function down()
    {
        Schema::table('field_activities', function (Blueprint $table) {
            $table->dropColumn(['reminder_date', 'deadline']);
        });
    }
};
