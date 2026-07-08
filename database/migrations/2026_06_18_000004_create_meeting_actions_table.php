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
        Schema::create('meeting_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('assigned_to');
            $table->date('due_date');
            $table->enum('status', ['Open', 'In Progress', 'Completed', 'Overdue'])->default('Open');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('created_by');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_actions');
    }
};
