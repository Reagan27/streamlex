<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('appraisals')) {
            Schema::create('appraisals', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('appraiser_id');
                $table->date('appraisal_date');
                $table->string('period');
                $table->integer('performance_score')->nullable();
                $table->integer('attitude_score')->nullable();
                $table->integer('communication_score')->nullable();
                $table->text('strengths')->nullable();
                $table->text('areas_for_improvement')->nullable();
                $table->text('goals')->nullable();
                $table->boolean('status')->default(false);
                $table->timestamps();

                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->foreign('appraiser_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('appraisals');
    }
};