<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_logistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_activity_id');
            $table->string('from');
            $table->string('to');
            $table->string('mode'); // Bus, Matatu, Car, etc
            $table->decimal('cost', 12, 2);
            $table->date('date')->nullable();
            $table->string('gps_departure')->nullable();
            $table->string('gps_arrival')->nullable();
            $table->string('status')->nullable(); // planned, actual, verified, etc
            $table->timestamps();

            $table->foreign('field_activity_id')->references('id')->on('field_activities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_activity_logistics');
    }
};
