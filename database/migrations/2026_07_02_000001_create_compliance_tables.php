<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('compliance_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('compliance_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('reference_number')->nullable();
            $table->string('regulatory_authority')->nullable();
            $table->string('department')->nullable();
            $table->string('responsible_officer')->nullable();
            $table->string('status')->default('active');
            $table->string('renewal_frequency')->nullable();
            $table->integer('reminder_period')->default(30);
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('next_renewal_date')->nullable();
            $table->string('document_path')->nullable();
            $table->string('document_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('last_reminder_at')->nullable();
            $table->string('last_reminder_stage')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('compliance_renewal_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_document_id')->constrained('compliance_documents')->cascadeOnDelete();
            $table->date('renewed_on');
            $table->text('notes')->nullable();
            $table->string('document_path')->nullable();
            $table->string('document_name')->nullable();
            $table->string('renewal_type')->default('renewal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_renewal_histories');
        Schema::dropIfExists('compliance_documents');
        Schema::dropIfExists('compliance_categories');
    }
};
