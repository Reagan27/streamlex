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
        Schema::create('back_to_office_report_attachments', function (Blueprint $table) {
            $table->id();

            // Explicit foreign key name to avoid MySQL 64-char limit
            $table->unsignedBigInteger('back_to_office_report_id');
            $table->foreign('back_to_office_report_id', 'bto_report_attach_fk')
                  ->references('id')
                  ->on('back_to_office_reports')
                  ->cascadeOnDelete();

            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->string('attachment_type')->nullable(); // e.g., 'attendance_list', 'photos', 'minutes', 'receipts'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_to_office_report_attachments');
    }
};
