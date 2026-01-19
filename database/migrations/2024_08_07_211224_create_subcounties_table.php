<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcountiesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('subcounties')) {
            Schema::create('subcounties', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('county_id')->constrained('counties')->onDelete('cascade');
                $table->timestamps();
            });
        } else {
            Schema::table('subcounties', function (Blueprint $table) {
                if (!Schema::hasColumn('subcounties', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('subcounties', 'county_id')) {
                    $table->foreignId('county_id')->constrained('counties')->onDelete('cascade');
                }
                if (!Schema::hasColumn('subcounties', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('subcounties', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('subcounties');
    }
}