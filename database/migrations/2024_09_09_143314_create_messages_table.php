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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->text('message');
            $table->string('category')->nullable();
            $table->timestamp('date')->useCurrent();
            $table->string('batch_id')->nullable();
            $table->string('group_id')->nullable();
            $table->string('company_id')->nullable();
            $table->string('ussid')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('status_message')->nullable();
            $table->decimal('message_cost')->nullable();
            $table->string('message_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
