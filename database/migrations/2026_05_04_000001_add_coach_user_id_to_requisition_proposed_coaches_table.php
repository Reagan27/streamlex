<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        $fkName = 'requisition_proposed_coaches_coach_user_id_foreign';
        if (Schema::hasColumn('requisition_proposed_coaches', 'coach_user_id')) {
            // Try to drop the foreign key if it exists
            try {
                DB::statement("ALTER TABLE requisition_proposed_coaches DROP FOREIGN KEY $fkName");
            } catch (\Exception $e) {}
            // Drop the column
            Schema::table('requisition_proposed_coaches', function (Blueprint $table) {
                $table->dropColumn('coach_user_id');
            });
        }
        // Add the column and foreign key
        Schema::table('requisition_proposed_coaches', function (Blueprint $table) {
            $table->unsignedInteger('coach_user_id')->nullable()->after('id');
            $table->foreign('coach_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down() {
        $fkName = 'requisition_proposed_coaches_coach_user_id_foreign';
        if (Schema::hasColumn('requisition_proposed_coaches', 'coach_user_id')) {
            // Try to drop the foreign key if it exists
            try {
                DB::statement("ALTER TABLE requisition_proposed_coaches DROP FOREIGN KEY $fkName");
            } catch (\Exception $e) {}
            // Drop the column
            Schema::table('requisition_proposed_coaches', function (Blueprint $table) {
                $table->dropColumn('coach_user_id');
            });
        }
    }
};
