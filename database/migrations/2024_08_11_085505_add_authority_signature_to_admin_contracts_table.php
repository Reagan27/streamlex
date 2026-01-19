<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_contracts', 'authority_signature')) {
                $table->string('authority_signature')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('admin_contracts', 'authority_signature')) {
                $table->dropColumn('authority_signature');
            }
        });
    }
};