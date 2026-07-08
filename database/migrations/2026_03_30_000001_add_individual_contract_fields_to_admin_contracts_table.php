<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_contracts', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('contract_category');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('admin_contracts', 'engagement_type')) {
                $table->string('engagement_type')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('admin_contracts', 'duration_type')) {
                $table->string('duration_type')->nullable()->after('engagement_type');
            }

            if (!Schema::hasColumn('admin_contracts', 'number_of_days')) {
                $table->integer('number_of_days')->nullable()->after('duration_type');
            }
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'engagement_type', 'duration_type', 'number_of_days']);
        });
    }
};