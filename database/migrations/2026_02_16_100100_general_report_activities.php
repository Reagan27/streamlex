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
        Schema::create('general_report_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_report_id')->constrained()->cascadeOnDelete();
            $table->string('activity_type');
            $table->text('description');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('outcomes')->nullable();
            $table->text('resources_used')->nullable();
            $table->text('challenges')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_report_activities');
    }
};
