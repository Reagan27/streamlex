<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_activity_id');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->date('date')->nullable();
            $table->string('type')->nullable(); // planned, actual, etc
            $table->timestamps();

            $table->foreign('field_activity_id')->references('id')->on('field_activities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_activity_expenses');
    }
};
