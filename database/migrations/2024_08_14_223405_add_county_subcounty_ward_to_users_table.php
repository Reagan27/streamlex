<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountySubcountyWardToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'county_id')) {
                $table->unsignedBigInteger('county_id')->nullable()->after('phone');
                $table->foreign('county_id')->references('id')->on('counties')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'subcounty_id')) {
                $table->unsignedBigInteger('subcounty_id')->nullable()->after('county_id');
                $table->foreign('subcounty_id')->references('id')->on('subcounties')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'ward_id')) {
                $table->unsignedBigInteger('ward_id')->nullable()->after('subcounty_id');
                $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['county_id']);
            $table->dropForeign(['subcounty_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['county_id', 'subcounty_id', 'ward_id']);
        });
    }
}
