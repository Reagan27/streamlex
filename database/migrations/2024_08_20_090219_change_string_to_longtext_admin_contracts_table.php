<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add new column
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->longText('authority_signature_new')->nullable();
        });

        // Copy data from old column to new column
        DB::statement('UPDATE admin_contracts SET authority_signature_new = authority_signature');

        // Drop old column
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->dropColumn('authority_signature');
        });

        // Rename new column to old column name
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->renameColumn('authority_signature_new', 'authority_signature');
        });
    }

    public function down()
    {
        // If you need to reverse this migration
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->string('authority_signature')->change();
        });
    }
};