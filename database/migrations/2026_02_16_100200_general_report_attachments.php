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
        Schema::create('general_report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_report_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_report_attachments');
    }
};
