<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->string('contract_category')->default('group')->after('id');
            $table->unsignedInteger('user_id')->nullable()->after('contract_category');
            $table->string('engagement_type')->nullable()->after('user_id');
            $table->string('duration_type')->nullable()->after('engagement_type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['contract_category', 'user_id', 'engagement_type', 'duration_type']);
        });
    }
};
