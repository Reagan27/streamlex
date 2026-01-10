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
        Schema::table('field_reports', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['approved_by']);
            
            // Then modify the column to be nullable
            $table->unsignedInteger('approved_by')->nullable()->change();
            
            // Re-add the foreign key constraint allowing null
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_reports', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['approved_by']);
            
            // Then modify the column to not be nullable
            $table->unsignedInteger('approved_by')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};