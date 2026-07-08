<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            if (!Schema::hasColumn('coach_requisitions', 'county_id')) {
                $table->unsignedBigInteger('county_id')->after('reporting_to');
            }
        });
    }
    public function down() {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            if (Schema::hasColumn('coach_requisitions', 'county_id')) {
                $table->dropColumn('county_id');
            }
        });
    }
};
