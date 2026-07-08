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
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->unsignedInteger('user_id');
            $table->enum('role', ['Chairperson', 'Secretary', 'Participant', 'Presenter', 'Guest'])->default('Participant');
            $table->enum('attendance_status', ['Invited', 'Confirmed', 'Declined', 'Attended'])->default('Invited');
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint
            $table->unique(['meeting_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_participants');
    }
};
