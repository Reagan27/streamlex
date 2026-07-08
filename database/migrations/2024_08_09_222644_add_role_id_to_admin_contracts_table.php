<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_contracts', 'role_id')) {
                $table->unsignedInteger('role_id')->nullable()->after('status');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            if (Schema::hasColumn('admin_contracts', 'role_id')) {
                $table->dropColumn('role_id');
            }
        });
    }


};