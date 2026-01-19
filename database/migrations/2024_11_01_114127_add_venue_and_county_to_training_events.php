<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('training_events', function (Blueprint $table) {
            $table->string('venue_name')->nullable()->after('name');
            $table->unsignedBigInteger('county_id')->nullable()->after('venue_name');
            $table->foreign('county_id')->references('id')->on('counties')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('training_events', function (Blueprint $table) {
            $table->dropForeign(['county_id']);
            $table->dropColumn(['venue_name', 'county_id']);
        });
    }
};
