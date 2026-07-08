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
            $table->foreignId('county_id')->nullable()->constrained('counties')->nullOnDelete();
        Schema::create('general_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // New: category field
            $table->string('location');
            $table->date('report_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('summary');
            $table->text('objectives');
            $table->text('challenges_faced')->nullable();
            $table->text('recommendations')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');

            // Fixed foreign keys: match users.id (int unsigned)
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            // County foreign key can stay as foreignId (bigint) if counties.id is bigint
            $table->foreignId('county_id')->nullable()->constrained('counties')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_reports');
    }
};
