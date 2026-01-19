<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotTables extends Migration
{
    public function up()
    {
        Schema::create('bot_messages', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->index();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name')->nullable(); 
            $table->string('phone'); 
            $table->string('recipient')->nullable(); 
            $table->text('message');
            $table->string('category')->default('bot'); 
            $table->timestamp('date')->nullable(); 
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable(); 
            $table->string('ussid')->nullable(); 
            $table->integer('status')->default(0); 
            $table->string('status_message')->nullable(); 
            $table->decimal('message_cost', 10, 2)->nullable();
            $table->string('message_id')->nullable();
            $table->text('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
        });

        Schema::create('bot_message_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->unsignedInteger('user_id')->nullable(); // changed
            $table->integer('total_count')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->text('filters')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('bot_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->unsignedInteger('user_id')->nullable(); // changed
            $table->string('phone_number');
            $table->string('rating_type');
            $table->unsignedBigInteger('rateable_id')->nullable();
            $table->string('rateable_type')->nullable();
            $table->integer('rating_score')->nullable();
            $table->enum('thumb_rating', ['up', 'down'])->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('bot_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_id')->unique();
            $table->unsignedInteger('user_id')->nullable(); // changed
            $table->string('phone_number');
            $table->enum('category', [
                'system_failure',
                'mentorship_coaching',
                'operations',
                'training',
                'payment',
                'harassment',
                'general'
            ]);
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->unsignedInteger('assigned_to')->nullable(); // changed
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('bot_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('message');
            $table->json('placeholders')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_templates');
        Schema::dropIfExists('bot_issues');
        Schema::dropIfExists('bot_ratings');
        Schema::dropIfExists('bot_message_batches');
        Schema::dropIfExists('bot_messages');
    }
}
