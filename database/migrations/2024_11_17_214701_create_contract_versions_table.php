<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contract_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('admin_contracts')
                  ->onDelete('cascade');
            $table->string('status');
            $table->text('description');
            $table->string('authority_signature')->nullable();
            $table->string('authority_name')->nullable();
            $table->string('authority_designation')->nullable();
            $table->text('change_reason')->nullable();
            
            $table->unsignedInteger('changed_by');
            $table->foreign('changed_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contract_versions');
    }
};
