<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('original_file_path')->nullable();
            $table->string('original_file_name')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('signature_page')->default(1);
            $table->decimal('signature_x', 8, 2)->default(20.00);
            $table->decimal('signature_y', 8, 2)->default(220.00);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_acknowledgements');
    }
};
