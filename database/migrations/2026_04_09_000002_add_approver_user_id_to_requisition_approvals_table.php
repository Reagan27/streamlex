<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisition_approvals', function (Blueprint $table) {
            $table->unsignedBigInteger('approver_user_id')->nullable()->after('approver_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition_approvals', function (Blueprint $table) {
            $table->dropColumn('approver_user_id');
        });
    }
};
