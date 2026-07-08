<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('regional_coordinator_counties')) {
            Schema::create('regional_coordinator_counties', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedBigInteger('county_id');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
            });
        } else {
            Schema::table('regional_coordinator_counties', function (Blueprint $table) {
                if (!Schema::hasColumn('regional_coordinator_counties', 'user_id')) {
                    $table->unsignedInteger('user_id');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('regional_coordinator_counties', 'county_id')) {
                    $table->unsignedBigInteger('county_id');
                    $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('regional_coordinator_counties');
    }
};