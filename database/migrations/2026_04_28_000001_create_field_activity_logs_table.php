<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_activity_id');
            $table->date('date');
            $table->json('log_data');
            $table->timestamps();
            $table->unique(['field_activity_id', 'date']);
            $table->foreign('field_activity_id')->references('id')->on('field_activities')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_activity_logs');
    }
};
