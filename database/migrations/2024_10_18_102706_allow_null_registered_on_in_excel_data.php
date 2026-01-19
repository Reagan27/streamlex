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
        Schema::table('excel_data', function (Blueprint $table) {
            $table->date('RegisteredOn')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('excel_data', function (Blueprint $table) {
            $table->date('RegisteredOn')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
};
