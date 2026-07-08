<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('projects_user', function (Blueprint $table) {
            $table->id();

            // users.id = INT UNSIGNED
            $table->unsignedInteger('user_id');

            // projects.id = BIGINT UNSIGNED
            $table->unsignedBigInteger('project_id');

            $table->boolean('is_active_project')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects_user');
    }
}
