<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('data_collections', function (Blueprint $table) {
            if (!Schema::hasColumn('data_collections', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('id');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
                $table->index('project_id');
            }
        });
    }

    public function down()
    {
        Schema::table('data_collections', function (Blueprint $table) {
            if (Schema::hasColumn('data_collections', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropIndex(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }
};
