<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('excel_data', function (Blueprint $table) {
            $table->id();
            $table->string('RegisteredOn')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('excel_data');
    }
};
