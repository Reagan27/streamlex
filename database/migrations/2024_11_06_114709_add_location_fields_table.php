<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('training_events', function (Blueprint $table) {
            $table->longText('location_token')->nullable()->after('slug');
        });
    }

    public function down()
    {
        Schema::table('training_events', function (Blueprint $table) {
            $table->dropColumn([
                'location_token',
            ]);
        });
    }
};