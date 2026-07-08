<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            $table->string('engagement_type')->nullable()->after('total_cost');
            $table->string('engagement_rate')->nullable()->after('engagement_type');
            $table->string('engagement_total')->nullable()->after('engagement_rate');
        });
    }
    public function down() {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            $table->dropColumn(['engagement_type', 'engagement_rate', 'engagement_total']);
        });
    }
};
