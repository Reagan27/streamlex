<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::create('county_region', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('county_id');
            $table->timestamps();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
            $table->unique(['region_id', 'county_id']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('county_region');
        Schema::dropIfExists('regions');
    }
};
