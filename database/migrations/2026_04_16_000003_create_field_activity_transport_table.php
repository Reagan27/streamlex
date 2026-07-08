<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_transport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_activity_id')->constrained()->onDelete('cascade');
            $table->string('from_location');
            $table->string('to_location');
            $table->string('mode');
            $table->decimal('planned_cost', 12, 2);
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->decimal('departure_lat', 10, 7)->nullable();
            $table->decimal('departure_lng', 10, 7)->nullable();
            $table->decimal('arrival_lat', 10, 7)->nullable();
            $table->decimal('arrival_lng', 10, 7)->nullable();
            $table->dateTime('departure_time')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->enum('gps_status', ['pending', 'verified'])->default('pending');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_activity_transport');
    }
};