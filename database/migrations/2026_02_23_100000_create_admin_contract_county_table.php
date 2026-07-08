<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('admin_contract_county', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_contract_id');
            $table->unsignedBigInteger('county_id');
            $table->timestamps();

            $table->foreign('admin_contract_id')->references('id')->on('admin_contracts')->onDelete('cascade');
            $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_contract_county');
    }
};
