<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_contract_signatures', function (Blueprint $table) {
            $table->text('decline_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('user_contract_signatures', function (Blueprint $table) {
            $table->dropColumn('decline_reason');
        });
    }
};
