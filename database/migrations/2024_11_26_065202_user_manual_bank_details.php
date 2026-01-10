<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_manual_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('manual_branch_name')->nullable();
            $table->string('manual_branch_code')->nullable();
            $table->boolean('use_manual_details')->default(false);
            $table->timestamps();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_manual_bank_details');
    }
};