<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_contract_signatures', function (Blueprint $table) {
            $table->enum('status', ['draft', 'approved','accepted', 'declined'])->default('draft');
        });
    }

    public function down()
    {
        Schema::table('user_contract_signatures', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

   
};
