<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_activity_id');
            $table->string('type'); // photo, receipt, report, etc
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('field_activity_id')->references('id')->on('field_activities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_activity_documents');
    }
};
