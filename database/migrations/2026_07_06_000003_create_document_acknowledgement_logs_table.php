<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_acknowledgement_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_acknowledgement_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('action');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('document_acknowledgement_id', 'fk_doc_ack_log_doc_id')
                ->references('id')
                ->on('document_acknowledgements')
                ->cascadeOnDelete();
            $table->foreign('user_id', 'fk_doc_ack_log_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_acknowledgement_logs');
    }
};
