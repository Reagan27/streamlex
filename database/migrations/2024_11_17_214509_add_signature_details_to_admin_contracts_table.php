<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->string('authority_name')->nullable()->after('authority_signature');
            $table->string('authority_designation')->nullable()->after('authority_name');
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->dropColumn(['authority_name', 'authority_designation']);
        });
    }
};
