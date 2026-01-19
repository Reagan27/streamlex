<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_issues', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->Integer('category_id');
            $table->string('priority')->default('Low');
            $table->string('subject');
            $table->text('content');
            $table->string('status')->default('Unresolved');
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('category_id')->references('id')->on('issues_categories')->onDelete('cascade');
        });

        Schema::create('issues_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_issues');
        Schema::dropIfExists('issues_categories');
    }
}
