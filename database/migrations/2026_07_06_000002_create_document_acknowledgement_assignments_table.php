<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_acknowledgement_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_acknowledgement_id');
            $table->unsignedInteger('user_id');
            $table->enum('status', ['Pending', 'Viewed', 'Signed'])->default('Pending');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('signed_file_path')->nullable();
            $table->timestamps();

            $table->foreign('document_acknowledgement_id', 'fk_doc_ack_assign_doc_id')
                ->references('id')
                ->on('document_acknowledgements')
                ->cascadeOnDelete();
            $table->foreign('user_id', 'fk_doc_ack_assign_user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_acknowledgement_assignments');
    }
};
