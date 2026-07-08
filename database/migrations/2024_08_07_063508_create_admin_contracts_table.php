<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('admin_contracts')) {
            Schema::create('admin_contracts', function (Blueprint $table) {
                $table->id();
                $table->date('start_date');
                $table->integer('number_of_days');
                $table->string('title');
                $table->text('description');
                $table->string('authority_signature')->nullable(); 
                $table->enum('status', ['draft', 'published', 'drop'])->default('draft');
                $table->unsignedInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('admin_contracts');
    }
};