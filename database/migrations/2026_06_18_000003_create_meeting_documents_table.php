<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meeting_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->enum('document_type', ['Minutes', 'Audio', 'Video', 'Attendance', 'Transcript'])->default('Minutes');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('uploaded_by');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_documents');
    }
};
