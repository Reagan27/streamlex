<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_contract_signatures', 'project_id')) {
            Schema::table('user_contract_signatures', function (Blueprint $table) {
                $table->unsignedBigInteger('project_id')->nullable()->after('contract_id');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
                $table->index('project_id');
            });
        }
    }

    public function down()
    {
        Schema::table('user_contract_signatures', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
};