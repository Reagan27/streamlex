<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('requisition_approvals', function (Blueprint $table) {
            $table->string('approver_name')->nullable();
            $table->unsignedBigInteger('approver_user_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('requisition_approvals', function (Blueprint $table) {
            $table->dropColumn(['approver_name', 'approver_user_id']);
        });
    }
};
