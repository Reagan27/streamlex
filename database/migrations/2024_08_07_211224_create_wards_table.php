<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWardsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('wards')) {
            Schema::create('wards', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('subcounty_id')->constrained('subcounties')->onDelete('cascade');
                $table->timestamps();
            });
        } else {
            Schema::table('wards', function (Blueprint $table) {
                if (!Schema::hasColumn('wards', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('wards', 'subcounty_id')) {
                    $table->foreignId('subcounty_id')->constrained('subcounties')->onDelete('cascade');
                }
                if (!Schema::hasColumn('wards', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('wards', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('wards');
    }
}