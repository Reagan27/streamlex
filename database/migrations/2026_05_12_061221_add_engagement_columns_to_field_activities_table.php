<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEngagementColumnsToFieldActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('field_activities', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('field_activities', 'engagement_type')) {
                $table->string('engagement_type')->nullable()->after('status');
            }
            if (!Schema::hasColumn('field_activities', 'engagement_rate')) {
                $table->decimal('engagement_rate', 10, 2)->nullable()->after('engagement_type');
            }
            if (!Schema::hasColumn('field_activities', 'engagement_total')) {
                $table->string('engagement_total')->nullable()->after('engagement_rate');
            }
            if (!Schema::hasColumn('field_activities', 'coach_requisition_id')) {
                $table->unsignedBigInteger('coach_requisition_id')->nullable()->after('requisition_id');
                $table->foreign('coach_requisition_id')
                      ->references('id')
                      ->on('coach_requisitions')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('field_activities', function (Blueprint $table) {
            $table->dropForeign(['coach_requisition_id']);
            $table->dropColumn([
                'engagement_type',
                'engagement_rate',
                'engagement_total',
                'coach_requisition_id',
            ]);
        });
    }
}