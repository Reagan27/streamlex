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
        // Columns already exist in payments table, nothing to add here.
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Columns already exist in payments table, nothing to drop here.
    }
};
