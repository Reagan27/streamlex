<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_activity_id')->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->string('file_size')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_activity_documents');
    }
};