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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('meeting_type', ['Physical', 'Virtual'])->default('Virtual');
            $table->enum('status', ['Draft', 'Scheduled', 'In Progress', 'Completed', 'Archived'])->default('Draft');
            
            // Meeting details
            $table->dateTime('meeting_date');
            $table->time('start_time');
            $table->time('end_time');
            
            // Physical meeting fields
            $table->string('venue_name')->nullable();
            $table->text('address')->nullable();
            $table->string('room_number')->nullable();
            $table->text('location_map_link')->nullable();
            
            // Virtual meeting fields
            $table->string('meeting_link')->nullable();
            $table->enum('meeting_platform', ['Zoom', 'Google Meet', 'Teams', 'Other'])->nullable();
            
            // Organizer
            $table->unsignedInteger('created_by');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
