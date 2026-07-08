<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_contracts', 'created_by')) {
                $table->unsignedInteger('created_by')->nullable()->after('status');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('admin_contracts', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
    }
};
