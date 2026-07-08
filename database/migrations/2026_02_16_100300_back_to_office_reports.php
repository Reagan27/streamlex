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
        Schema::create('back_to_office_reports', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->string('area')->nullable();
            $table->string('unit')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('reported_by')->nullable();
            $table->text('activity')->nullable();
            $table->string('venue')->nullable();
            
            // Participants and budget (stored as JSON)
            $table->json('participants')->nullable();
            $table->json('budget')->nullable();
            
            // Report sections
            $table->text('introduction')->nullable();
            $table->text('objective')->nullable();
            $table->json('budget_expenditure')->nullable(); // {planned, actual, variance, comment}
            $table->text('output')->nullable();
            $table->text('key_highlights')->nullable();
            $table->text('challenges_and_risks')->nullable();
            $table->text('best_practices')->nullable();
            $table->text('lessons_learnt')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('way_forward')->nullable();
            
            // Officer details
            $table->string('prepared_by')->nullable();
            $table->date('prepared_date')->nullable();
            $table->string('signature')->nullable();
            
            // Annexes
            $table->json('annexes')->nullable();
            
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');

            // FIXED foreign keys: match users.id (int unsigned)
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->foreignId('county_id')->nullable()->constrained('counties')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_to_office_reports');
    }
};
