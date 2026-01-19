<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('assets_assignment', function (Blueprint $table) {
            $table->text('return_condition')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('assets_assignment', function (Blueprint $table) {
            $table->dropColumn('return_condition');
        });
    }
};
