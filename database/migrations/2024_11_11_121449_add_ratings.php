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
        // 'hidden' column already exists in ratings table. No action needed.
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // No action needed. Column was not added here.
    }
};
