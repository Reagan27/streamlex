<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('coach_requisitions', function (Blueprint $table) {
            $table->string('title')->after('id')->nullable();
        });
        Schema::table('field_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('requisition_id')->nullable()->after('id');
            $table->foreign('requisition_id')->references('id')->on('coach_requisitions')->onDelete('set null');
        });
    }
    public function down() {
        Schema::table('field_activities', function (Blueprint $table) {
            $table->dropForeign(['requisition_id']);
            $table->dropColumn('requisition_id');
        });
        Schema::table('coach_requisitions', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
