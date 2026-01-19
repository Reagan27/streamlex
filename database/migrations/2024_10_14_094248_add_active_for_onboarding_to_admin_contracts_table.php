<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->boolean('active_for_onboarding')->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            $table->dropColumn('active_for_onboarding');
        });
    }
};
