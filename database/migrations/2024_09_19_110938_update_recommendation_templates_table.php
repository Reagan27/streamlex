<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('recommendation_templates', function (Blueprint $table) {
            $table->enum('type', ['recommendation', 'certificate'])->after('name')->default('recommendation');
        });

        Schema::rename('recommendation_templates', 'recommendation_certificates');
    }

    public function down()
    {
        Schema::rename('recommendation_certificates', 'recommendation_templates');

        Schema::table('recommendation_templates', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
